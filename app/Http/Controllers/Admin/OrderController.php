<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\SmmRajaService;
use Illuminate\Http\Request;
use Exception;

class OrderController extends Controller
{
    protected SmmRajaService $smmService;

    public function __construct(SmmRajaService $smmService)
    {
        $this->smmService = $smmService;
    }

    /**
     * Display orders list
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        $userId = $request->get('user_id');
        $search = $request->get('search');

        $query = Order::with(['user', 'service.category'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('api_order_id', $search)
                    ->orWhere('link', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::whereIn('status', ['processing', 'in_progress'])->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'partial' => Order::where('status', 'partial')->count(),
            'canceled' => Order::where('status', 'canceled')->count(),
            'refunded' => Order::where('status', 'refunded')->count(),
            'error' => Order::where('status', 'error')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'status', 'statusCounts', 'search'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        $order->load(['user', 'service.category']);

        // Try to get current status from API
        $apiStatus = null;
        if ($order->api_order_id) {
            try {
                $apiStatus = $this->smmService->getOrderStatus($order->api_order_id);
            } catch (Exception $e) {
                $apiStatus = ['error' => $e->getMessage()];
            }
        }

        return view('admin.orders.show', compact('order', 'apiStatus'));
    }

    /**
     * Check and update orders status from API
     */
    public function checkStatus(Request $request)
    {
        $orderIds = $request->get('order_ids', []);
        
        if (empty($orderIds)) {
            // Get all pending orders
            $orders = Order::needsStatusCheck()->limit(100)->get();
        } else {
            $orders = Order::whereIn('id', $orderIds)->get();
        }

        if ($orders->isEmpty()) {
            return back()->with('info', 'Không có đơn hàng nào cần kiểm tra.');
        }

        $apiOrderIds = $orders->pluck('api_order_id')->filter()->toArray();
        
        if (empty($apiOrderIds)) {
            return back()->with('info', 'Không có đơn hàng nào có API Order ID.');
        }

        try {
            $statuses = $this->smmService->getMultipleOrderStatus($apiOrderIds);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Lỗi API: ' . $e->getMessage()]);
        }

        $updated = 0;

        foreach ($orders as $order) {
            if (!$order->api_order_id) continue;

            $statusData = $statuses[$order->api_order_id] ?? null;
            if (!$statusData || isset($statusData['error'])) continue;

            $newStatus = $this->smmService->mapStatus($statusData['status'] ?? '');
            
            $order->update([
                'status' => $newStatus ?: $order->status,
                'start_count' => $statusData['start_count'] ?? $order->start_count,
                'remains' => $statusData['remains'] ?? $order->remains,
                'api_charge' => $statusData['charge'] ?? $order->api_charge,
            ]);

            $updated++;
        }

        return back()->with('success', "Đã cập nhật trạng thái {$updated} đơn hàng.");
    }
    
    /**
     * Show refund pending orders
     */
    public function refunds()
    {
        $orders = Order::with(['user', 'service.category'])
            ->whereIn('status', ['cancel_pending', 'partial', 'canceled'])
            ->latest()
            ->paginate(20);
            
        $counts = [
            'cancel_pending' => Order::where('status', 'cancel_pending')->count(),
            'partial' => Order::where('status', 'partial')->count(),
            'canceled' => Order::where('status', 'canceled')->count(),
        ];
        
        return view('admin.orders.refunds', compact('orders', 'counts'));
    }
    
    /**
     * Approve refund for an order
     */
    public function approveRefund(Request $request, Order $order)
    {
        if (!in_array($order->status, ['cancel_pending', 'partial', 'canceled'])) {
            return back()->withErrors(['error' => 'Đơn hàng này không trong trạng thái cần hoàn tiền.']);
        }
        
        $refundType = $request->get('refund_type', 'full'); // full or partial
        $refundAmount = 0;
        
        if ($refundType === 'full') {
            $refundAmount = $order->total_price;
        } else {
            // Partial refund based on remains or custom amount
            $remains = $order->remains ?? 0;
            if ($remains > 0) {
                $refundAmount = $order->price_per_unit * $remains;
            } else {
                $refundAmount = (float) $request->get('refund_amount', 0);
            }
        }
        
        if ($refundAmount <= 0) {
            return back()->withErrors(['error' => 'Số tiền hoàn không hợp lệ.']);
        }
        
        // Add balance to user
        $user = $order->user;
        $user->addBalance(
            $refundAmount,
            'refund',
            "Hoàn tiền đơn hàng #{$order->id}",
            $order->id
        );
        
        // Update order status
        $order->update(['status' => 'refunded']);
        
        // Send notification
        \App\Models\Notification::depositSuccess($user->id, $refundAmount);
        
        return back()->with('success', "Đã hoàn " . number_format($refundAmount, 0) . " VND cho đơn hàng #{$order->id}.");
    }
    
    /**
     * Reject refund (mark as canceled without refund)
     */
    public function rejectRefund(Order $order)
    {
        if ($order->status !== 'cancel_pending') {
            return back()->withErrors(['error' => 'Đơn hàng này không trong trạng thái chờ hoàn tiền.']);
        }
        
        $order->update(['status' => 'canceled']);
        
        return back()->with('success', "Đã từ chối hoàn tiền cho đơn hàng #{$order->id}.");
    }
}

