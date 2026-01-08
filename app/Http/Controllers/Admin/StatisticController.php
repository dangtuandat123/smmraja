<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\PageView;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'month'); // month, year, all
        $month = $request->get('month', now()->month);
        $year = $request->get('year', now()->year);

        // Build date range
        $dateRange = $this->getDateRange($period, $month, $year);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Get exchange rate for profit calculation
        $exchangeRate = ExchangeRateService::getRate();

        // === REVENUE & PROFIT ===
        $ordersQuery = Order::whereBetween('created_at', [$startDate, $endDate]);
        
        $totalRevenue = (clone $ordersQuery)->sum('total_price');
        $totalApiCost = (clone $ordersQuery)->sum('api_charge') * $exchangeRate;
        $totalProfit = $totalRevenue - $totalApiCost;
        $totalOrders = (clone $ordersQuery)->count();
        $completedOrders = (clone $ordersQuery)->where('status', 'completed')->count();

        // === DEPOSITS ===
        $depositsQuery = Transaction::where('type', 'deposit')
            ->where('amount', '>', 0)
            ->whereBetween('created_at', [$startDate, $endDate]);
        
        $totalDeposits = (clone $depositsQuery)->sum('amount');
        $depositCount = (clone $depositsQuery)->count();

        // === USERS ===
        $newUsers = User::whereBetween('created_at', [$startDate, $endDate])->count();
        $totalUsers = User::count();

        // === PAGE VIEWS ===
        $pageViews = PageView::whereBetween('viewed_date', [$startDate->toDateString(), $endDate->toDateString()])->count();
        $uniqueVisitors = PageView::whereBetween('viewed_date', [$startDate->toDateString(), $endDate->toDateString()])
            ->distinct('ip_address')
            ->count('ip_address');

        // === CHART DATA ===
        $chartData = $this->getChartData($period, $month, $year, $exchangeRate);

        // === TOP SERVICES ===
        $topServices = Order::select('service_id', DB::raw('SUM(total_price) as revenue'), DB::raw('COUNT(*) as order_count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('service_id')
            ->orderByDesc('revenue')
            ->limit(10)
            ->with('service:id,name')
            ->get();

        // === TOP CUSTOMERS (by deposit) ===
        $topDepositors = Transaction::select('user_id', DB::raw('SUM(amount) as total_deposit'))
            ->where('type', 'deposit')
            ->where('amount', '>', 0)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('user_id')
            ->orderByDesc('total_deposit')
            ->limit(10)
            ->with('user:id,name,email')
            ->get();

        // === TOP CUSTOMERS (by spending) ===
        $topSpenders = Order::select('user_id', DB::raw('SUM(total_price) as total_spent'), DB::raw('COUNT(*) as order_count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->with('user:id,name,email')
            ->get();

        // === COMPARISON WITH PREVIOUS PERIOD ===
        $previousRange = $this->getPreviousDateRange($period, $month, $year);
        $previousRevenue = Order::whereBetween('created_at', [$previousRange['start'], $previousRange['end']])->sum('total_price');
        $previousDeposits = Transaction::where('type', 'deposit')->where('amount', '>', 0)->whereBetween('created_at', [$previousRange['start'], $previousRange['end']])->sum('amount');
        
        $revenueChange = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;
        $depositChange = $previousDeposits > 0 ? (($totalDeposits - $previousDeposits) / $previousDeposits) * 100 : 0;

        return view('admin.statistics.index', compact(
            'period', 'month', 'year',
            'totalRevenue', 'totalProfit', 'totalApiCost', 'totalOrders', 'completedOrders',
            'totalDeposits', 'depositCount',
            'newUsers', 'totalUsers',
            'pageViews', 'uniqueVisitors',
            'chartData',
            'topServices', 'topDepositors', 'topSpenders',
            'revenueChange', 'depositChange',
            'startDate', 'endDate'
        ));
    }

    protected function getDateRange(string $period, int $month, int $year): array
    {
        switch ($period) {
            case 'month':
                $start = Carbon::create($year, $month, 1)->startOfMonth();
                $end = Carbon::create($year, $month, 1)->endOfMonth();
                break;
            case 'year':
                $start = Carbon::create($year, 1, 1)->startOfYear();
                $end = Carbon::create($year, 12, 31)->endOfYear();
                break;
            case 'all':
            default:
                $start = Carbon::create(2020, 1, 1);
                $end = Carbon::now();
                break;
        }

        return ['start' => $start, 'end' => $end];
    }

    protected function getPreviousDateRange(string $period, int $month, int $year): array
    {
        switch ($period) {
            case 'month':
                $prevDate = Carbon::create($year, $month, 1)->subMonth();
                return $this->getDateRange('month', $prevDate->month, $prevDate->year);
            case 'year':
                return $this->getDateRange('year', $month, $year - 1);
            default:
                return ['start' => Carbon::create(2020, 1, 1), 'end' => Carbon::create(2020, 1, 1)];
        }
    }

    protected function getChartData(string $period, int $month, int $year, float $exchangeRate): array
    {
        $labels = [];
        $revenueData = [];
        $profitData = [];
        $depositData = [];

        if ($period === 'month') {
            // Daily data for the month
            $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;
            
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::create($year, $month, $day);
                $labels[] = $day;
                
                $dayOrders = Order::whereDate('created_at', $date);
                $revenue = (clone $dayOrders)->sum('total_price');
                $apiCost = (clone $dayOrders)->sum('api_charge') * $exchangeRate;
                
                $revenueData[] = round($revenue);
                $profitData[] = round($revenue - $apiCost);
                $depositData[] = round(Transaction::where('type', 'deposit')->where('amount', '>', 0)->whereDate('created_at', $date)->sum('amount'));
            }
        } else {
            // Monthly data for the year
            for ($m = 1; $m <= 12; $m++) {
                $startOfMonth = Carbon::create($year, $m, 1)->startOfMonth();
                $endOfMonth = Carbon::create($year, $m, 1)->endOfMonth();
                
                $labels[] = 'T' . $m;
                
                $monthOrders = Order::whereBetween('created_at', [$startOfMonth, $endOfMonth]);
                $revenue = (clone $monthOrders)->sum('total_price');
                $apiCost = (clone $monthOrders)->sum('api_charge') * $exchangeRate;
                
                $revenueData[] = round($revenue);
                $profitData[] = round($revenue - $apiCost);
                $depositData[] = round(Transaction::where('type', 'deposit')->where('amount', '>', 0)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('amount'));
            }
        }

        return [
            'labels' => $labels,
            'revenue' => $revenueData,
            'profit' => $profitData,
            'deposits' => $depositData,
        ];
    }
}
