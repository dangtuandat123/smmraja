<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use App\Services\SmmRajaService;
use Illuminate\Http\Request;
use Exception;

class DashboardController extends Controller
{
    /**
     * Admin dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_orders' => Order::count(),
            'total_services' => Service::where('is_active', true)->count(),
            'total_revenue' => Order::whereIn('status', ['completed', 'partial'])->sum('total_price'),
            'pending_orders' => Order::whereIn('status', ['pending', 'processing', 'in_progress'])->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())->whereIn('status', ['completed', 'partial'])->sum('total_price'),
        ];

        $recentOrders = Order::with(['user', 'service'])
            ->latest()
            ->limit(10)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->where('type', 'deposit')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentTransactions'));
    }

    /**
     * Get API balance
     */
    public function apiBalance(SmmRajaService $smmService)
    {
        try {
            $balance = $smmService->getBalance();
            return response()->json([
                'success' => true,
                'balance' => $balance['balance'] ?? 0,
                'currency' => $balance['currency'] ?? 'USD',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
