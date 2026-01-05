<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use App\Services\SmmRajaService;
use App\Services\ExchangeRateService;
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

        // Get exchange rate info
        $exchangeRate = ExchangeRateService::getRate();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'recentTransactions', 'exchangeRate'));
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

    /**
     * Get exchange rate (API endpoint)
     */
    public function exchangeRate()
    {
        try {
            $rate = ExchangeRateService::getRate();
            return response()->json([
                'success' => true,
                'rate' => $rate,
                'formatted' => number_format($rate, 0, ',', '.'),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh exchange rate from API
     */
    public function refreshExchangeRate()
    {
        try {
            $rate = ExchangeRateService::refresh();
            return response()->json([
                'success' => true,
                'rate' => $rate,
                'formatted' => number_format($rate, 0, ',', '.'),
                'message' => 'Tỷ giá đã được cập nhật!',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
