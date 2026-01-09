<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\Notification;
use App\Services\SmmRajaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class OrderController extends Controller
{
    protected SmmRajaService $smmService;

    public function __construct(SmmRajaService $smmService)
    {
        $this->smmService = $smmService;
    }

    /**
     * Display order form
     */
    public function create(Request $request)
    {
        $categories = Category::active()
            ->ordered()
            ->with(['services' => function ($query) {
                $query->where('is_active', true)->orderBy('price_vnd', 'asc');
            }])
            ->get();

        $selectedCategory = $request->get('category');
        $selectedService = $request->get('service');

        return view('orders.create', compact('categories', 'selectedCategory', 'selectedService'));
    }

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => ['required', 'exists:services,id'],
            'link' => ['required', 'url'],
            'quantity' => ['required', 'integer', 'min:1'],
        ], [
            'service_id.required' => 'Vui lòng chọn dịch vụ.',
            'service_id.exists' => 'Dịch vụ không tồn tại.',
            'link.required' => 'Vui lòng nhập link.',
            'link.url' => 'Link không hợp lệ.',
            'quantity.required_unless' => 'Vui lòng nhập số lượng.',
            'quantity.integer' => 'Số lượng phải là số nguyên.',
            'quantity.min' => 'Số lượng phải lớn hơn 0.',
        ]);

        $service = Service::findOrFail($request->service_id);

        if (!$service->is_active) {
            return back()->withErrors(['service_id' => 'Dịch vụ này hiện không khả dụng.']);
        }

        // Validate quantity against min/max
        $quantity = (int) $request->quantity;
        if ($quantity < $service->min || $quantity > $service->max) {
            return back()->withErrors([
                'quantity' => "Số lượng phải từ {$service->min} đến {$service->max}."
            ])->withInput();
        }

        // Calculate total price
        $pricePerUnit = $service->price_per_unit;
        $totalPrice = $service->calculateOrderTotal($quantity);

        // Check user balance
        $user = auth()->user();
        if (!$user->hasBalance($totalPrice)) {
            return back()->withErrors([
                'balance' => 'Số dư không đủ. Vui lòng nạp thêm tiền.'
            ])->withInput();
        }

        DB::beginTransaction();
        try {
            // Deduct balance
            $user->deductBalance($totalPrice, 'order', "Đặt hàng dịch vụ: {$service->name}");

            // Build extra data based on service type
            $extraData = $this->buildExtraData($service, $request);

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'service_id' => $service->id,
                'link' => $request->link,
                'quantity' => $quantity,
                'price_per_unit' => $pricePerUnit,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'extra_data' => $extraData,
            ]);

            // Try to place order via API
            try {
                $orderData = array_merge(
                    ['link' => $request->link, 'quantity' => $quantity],
                    $extraData
                );

                $apiOrderId = $this->smmService->addOrder($service, $orderData);
                
                $order->update([
                    'api_order_id' => $apiOrderId,
                    'status' => 'processing',
                ]);
            } catch (Exception $e) {
                // API failed - refund and mark as error
                $user->addBalance($totalPrice, 'refund', "Hoàn tiền do lỗi API: {$e->getMessage()}", $order->id);
                
                $order->update([
                    'status' => 'error',
                    'error_message' => $e->getMessage(),
                ]);

                Log::error('Order API Error', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);

                DB::commit();
                return back()->withErrors([
                    'api' => 'Đặt hàng thất bại: ' . $e->getMessage()
                ])->withInput();
            }

            DB::commit();

            // Send notification
            Notification::orderCreated(
                auth()->id(),
                $order->id,
                $service->name
            );

            return redirect()->route('orders.show', $order)
                ->with('success', 'Đặt hàng thành công! Mã đơn hàng: #' . $order->id);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order Error', ['error' => $e->getMessage()]);
            
            return back()->withErrors([
                'error' => 'Có lỗi xảy ra. Vui lòng thử lại.'
            ])->withInput();
        }
    }

    /**
     * Build extra data based on service type
     */
    protected function buildExtraData(Service $service, Request $request): array
    {
        $type = strtolower($service->type);
        $data = [];

        switch ($type) {
            case 'custom comments':
            case 'custom comments package':
            case 'comment replies':
                if ($request->has('comments')) {
                    $data['comments'] = array_filter(explode("\n", $request->comments));
                }
                if ($request->has('username')) {
                    $data['username'] = $request->username;
                }
                break;

            case 'mentions':
            case 'mentions user followers':
            case 'comment likes':
                if ($request->has('username')) {
                    $data['username'] = $request->username;
                }
                break;

            case 'mentions with hashtags':
                if ($request->has('hashtag')) {
                    $data['hashtag'] = $request->hashtag;
                }
                if ($request->has('hashtags')) {
                    $data['hashtags'] = array_filter(explode("\n", $request->hashtags));
                }
                if ($request->has('usernames')) {
                    $data['usernames'] = array_filter(explode("\n", $request->usernames));
                }
                break;

            case 'mentions custom list':
                if ($request->has('usernames')) {
                    $data['usernames'] = array_filter(explode("\n", $request->usernames));
                }
                break;

            case 'mentions hashtag':
                if ($request->has('hashtag')) {
                    $data['hashtag'] = $request->hashtag;
                }
                break;

            case 'mentions media likers':
                if ($request->has('media')) {
                    $data['media'] = $request->media;
                }
                break;

            case 'poll':
                if ($request->has('answer_number')) {
                    $data['answer_number'] = (int) $request->answer_number;
                }
                break;

            case 'invites from groups':
                if ($request->has('groups')) {
                    $data['groups'] = array_filter(explode("\n", $request->groups));
                }
                break;

            case 'subscriptions':
            case 'package subscriptions':
                $data['username'] = $request->username ?? '';
                $data['min'] = (int) ($request->min ?? 10);
                $data['max'] = (int) ($request->max ?? 100);
                $data['delay'] = (int) ($request->delay ?? 5);
                if ($request->has('posts')) {
                    $data['posts'] = (int) $request->posts;
                }
                if ($request->has('expiry')) {
                    $data['expiry'] = $request->expiry;
                }
                break;
        }

        return $data;
    }

    /**
     * Display user's orders
     */
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = Order::forUser(auth()->id())
            ->with('service.category')
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->paginate(20);

        $statusCounts = [
            'all' => Order::forUser(auth()->id())->count(),
            'pending' => Order::forUser(auth()->id())->where('status', 'pending')->count(),
            'processing' => Order::forUser(auth()->id())->whereIn('status', ['processing', 'in_progress'])->count(),
            'completed' => Order::forUser(auth()->id())->where('status', 'completed')->count(),
        ];

        return view('orders.index', compact('orders', 'status', 'statusCounts'));
    }

    /**
     * Show order details
     */
    public function show(Order $order)
    {
        // Ensure user owns this order
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load('service.category');

        return view('orders.show', compact('order'));
    }

    /**
     * Request order refill
     */
    public function refill(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->canRefill()) {
            return back()->withErrors(['error' => 'Đơn hàng này không hỗ trợ refill.']);
        }

        try {
            $this->smmService->refillOrder($order->api_order_id);
            
            // Update status to track refill
            $order->update([
                'status' => 'refill_pending',
                'refill_requested_at' => now(),
            ]);
            
            return back()->with('success', 'Yêu cầu bảo hành đã được gửi! Đơn hàng sẽ được xử lý lại.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Bảo hành thất bại: ' . $e->getMessage()]);
        }
    }

    /**
     * Request order cancellation
     * Sends request to SMM Raja and marks order as pending cancellation
     * Admin will confirm refund after checking SMM Raja
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$order->canCancel()) {
            return back()->withErrors(['error' => 'Đơn hàng này không thể hủy.']);
        }

        try {
            $this->smmService->cancelOrder($order->api_order_id);
            
            // Mark as pending cancellation - admin will confirm refund
            $order->update(['status' => 'cancel_pending']);
            
            return back()->with('success', 'Yêu cầu hủy đã được gửi! Tiền sẽ được hoàn sau khi Admin xác nhận với SMM Raja.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Hủy thất bại: ' . $e->getMessage()]);
        }
    }
}
