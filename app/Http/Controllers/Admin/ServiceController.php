<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use App\Models\Setting;
use App\Services\SmmRajaService;
use App\Services\ExchangeRateService;
use Illuminate\Http\Request;
use Exception;

class ServiceController extends Controller
{
    protected SmmRajaService $smmService;

    public function __construct(SmmRajaService $smmService)
    {
        $this->smmService = $smmService;
    }

    /**
     * Display services list
     */
    public function index(Request $request)
    {
        $categoryId = $request->get('category');
        $search = $request->get('search');

        $query = Service::with('category')->ordered();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('api_name', 'like', "%{$search}%");
            });
        }

        $services = $query->paginate(20);
        $categories = Category::ordered()->get();

        return view('admin.services.index', compact('services', 'categories', 'categoryId', 'search'));
    }

    /**
     * Show import form
     */
    public function import()
    {
        $categories = Category::ordered()->get();
        
        try {
            $apiServices = $this->smmService->getServices(true);
        } catch (Exception $e) {
            $apiServices = [];
            session()->flash('error', 'Không thể lấy danh sách dịch vụ từ API: ' . $e->getMessage());
        }

        // Group by category
        $groupedServices = [];
        foreach ($apiServices as $service) {
            $category = $service['category'] ?? 'Uncategorized';
            if (!isset($groupedServices[$category])) {
                $groupedServices[$category] = [];
            }
            $groupedServices[$category][] = $service;
        }

        $existingServiceIds = Service::pluck('api_service_id')->toArray();

        return view('admin.services.import', compact('categories', 'groupedServices', 'existingServiceIds'));
    }

    /**
     * Process import
     */
    public function doImport(Request $request)
    {
        $request->validate([
            'services' => ['required', 'array', 'min:1'],
            'services.*' => ['required', 'integer'],
            'category_id' => ['required', 'exists:categories,id'],
            'markup_percent' => ['required', 'numeric', 'min:0', 'max:1000'],
        ]);

        $categoryId = $request->category_id;
        $markupPercent = (float) $request->markup_percent;
        $exchangeRate = ExchangeRateService::getRate();

        try {
            $apiServices = $this->smmService->getServices();
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Không thể lấy dữ liệu API: ' . $e->getMessage()]);
        }

        $importCount = 0;

        foreach ($request->services as $apiServiceId) {
            // Find service in API response
            $apiService = null;
            foreach ($apiServices as $s) {
                if ($s['service'] == $apiServiceId) {
                    $apiService = $s;
                    break;
                }
            }

            if (!$apiService) continue;

            // Check if already exists
            $existing = Service::where('api_service_id', $apiServiceId)->first();
            if ($existing) continue;

            // Calculate VND price
            $apiRate = (float) $apiService['rate'];
            $priceVnd = round($apiRate * (1 + $markupPercent / 100) * $exchangeRate, 2);

            Service::create([
                'category_id' => $categoryId,
                'api_service_id' => $apiServiceId,
                'name' => $apiService['name'],
                'api_name' => $apiService['name'],
                'type' => $apiService['type'] ?? 'Default',
                'description' => $apiService['description'] ?? '',
                'api_rate' => $apiRate,
                'markup_percent' => $markupPercent,
                'price_vnd' => $priceVnd,
                'min' => (int) ($apiService['min'] ?? 10),
                'max' => (int) ($apiService['max'] ?? 10000),
                'refill' => (bool) ($apiService['refill'] ?? false),
                'cancel' => (bool) ($apiService['cancel'] ?? false),
                'is_active' => true,
                'extra_parameters' => $apiService['extra_parameter'] ?? null,
            ]);

            $importCount++;
        }

        return redirect()->route('admin.services.index')
            ->with('success', "Đã import {$importCount} dịch vụ thành công!");
    }

    /**
     * Sync prices from API
     */
    public function syncPrices(Request $request)
    {
        try {
            $apiServices = $this->smmService->getServices(true);
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Không thể lấy dữ liệu API: ' . $e->getMessage()]);
        }

        $exchangeRate = ExchangeRateService::getRate();
        $updateCount = 0;

        foreach ($apiServices as $apiService) {
            $service = Service::where('api_service_id', $apiService['service'])->first();
            if (!$service) continue;

            $newApiRate = (float) $apiService['rate'];
            
            // Only update if price changed
            if ($service->api_rate != $newApiRate) {
                $service->api_rate = $newApiRate;
                $service->updatePriceVnd($exchangeRate);
                $updateCount++;
            }
        }

        return back()->with('success', "Đã cập nhật giá cho {$updateCount} dịch vụ!");
    }

    /**
     * Show edit form
     */
    public function edit(Service $service)
    {
        $categories = Category::ordered()->get();
        return view('admin.services.edit', compact('service', 'categories'));
    }

    /**
     * Update service
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'markup_percent' => ['required', 'numeric', 'min:0', 'max:1000'],
            'min' => ['required', 'integer', 'min:1'],
            'max' => ['required', 'integer', 'min:1'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['markup_percent'] = (float) $validated['markup_percent'];

        $service->fill($validated);
        
        // Recalculate price if markup changed
        $exchangeRate = ExchangeRateService::getRate();
        $service->price_vnd = $service->calculatePriceVnd($exchangeRate);
        
        $service->save();

        return redirect()->route('admin.services.index')
            ->with('success', 'Dịch vụ đã được cập nhật!');
    }

    /**
     * Delete service
     */
    public function destroy(Service $service)
    {
        if ($service->orders()->count() > 0) {
            return back()->withErrors(['error' => 'Không thể xóa dịch vụ có đơn hàng.']);
        }

        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Dịch vụ đã được xóa!');
    }
}
