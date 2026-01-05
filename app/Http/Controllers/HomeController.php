<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Homepage
     */
    public function index()
    {
        $categories = Category::active()
            ->ordered()
            ->withCount(['services' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $featuredServices = Service::active()
            ->ordered()
            ->with('category')
            ->limit(8)
            ->get();

        return view('home', compact('categories', 'featuredServices'));
    }

    /**
     * User dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        $recentOrders = Order::forUser($user->id)
            ->with('service.category')
            ->latest()
            ->limit(5)
            ->get();

        $stats = [
            'total_orders' => Order::forUser($user->id)->count(),
            'pending_orders' => Order::forUser($user->id)->whereIn('status', ['pending', 'processing', 'in_progress'])->count(),
            'completed_orders' => Order::forUser($user->id)->where('status', 'completed')->count(),
            'total_spent' => Order::forUser($user->id)->sum('total_price'),
        ];

        return view('dashboard', compact('user', 'recentOrders', 'stats'));
    }
}
