<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display services list
     */
    public function index(Request $request)
    {
        $categorySlug = $request->get('category');
        $search = $request->get('search');
        $sort = $request->get('sort', 'default');
        $refill = $request->get('refill');
        $cancel = $request->get('cancel');

        $categories = Category::active()
            ->ordered()
            ->withCount(['services' => function ($query) {
                $query->where('services.is_active', true);
            }])
            ->get();

        $servicesQuery = Service::active()
            ->with('category');

        if ($categorySlug) {
            $servicesQuery->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        if ($search) {
            $servicesQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by refill
        if ($refill === '1') {
            $servicesQuery->where('refill', true);
        }

        // Filter by cancel
        if ($cancel === '1') {
            $servicesQuery->where('cancel', true);
        }

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                // Khi chọn "Tất cả", vẫn nhóm theo category trước
                if (!$categorySlug) {
                    $servicesQuery->join('categories', 'services.category_id', '=', 'categories.id')
                        ->orderBy('categories.sort_order')
                        ->orderBy('categories.name')
                        ->select('services.*');
                }
                $servicesQuery->orderBy('price_vnd', 'asc');
                break;
            case 'price_desc':
                if (!$categorySlug) {
                    $servicesQuery->join('categories', 'services.category_id', '=', 'categories.id')
                        ->orderBy('categories.sort_order')
                        ->orderBy('categories.name')
                        ->select('services.*');
                }
                $servicesQuery->orderBy('price_vnd', 'desc');
                break;
            case 'name_asc':
                if (!$categorySlug) {
                    $servicesQuery->join('categories', 'services.category_id', '=', 'categories.id')
                        ->orderBy('categories.sort_order')
                        ->orderBy('categories.name')
                        ->select('services.*');
                }
                $servicesQuery->orderBy('services.name', 'asc');
                break;
            case 'newest':
                if (!$categorySlug) {
                    $servicesQuery->join('categories', 'services.category_id', '=', 'categories.id')
                        ->orderBy('categories.sort_order')
                        ->orderBy('categories.name')
                        ->select('services.*');
                }
                $servicesQuery->orderBy('services.created_at', 'desc');
                break;
            default:
                // Mặc định: sắp xếp theo category trước, rồi theo sort_order của service
                if (!$categorySlug) {
                    $servicesQuery->join('categories', 'services.category_id', '=', 'categories.id')
                        ->orderBy('categories.sort_order')
                        ->orderBy('categories.name')
                        ->orderBy('services.sort_order')
                        ->orderBy('services.name')
                        ->select('services.*');
                } else {
                    $servicesQuery->ordered();
                }
                break;
        }

        $services = $servicesQuery->paginate(20)->appends($request->query());

        return view('services.index', compact('categories', 'services', 'categorySlug', 'search', 'sort', 'refill', 'cancel'));
    }

    /**
     * Get service details (API)
     */
    public function show(Service $service)
    {
        if (!$service->is_active) {
            return response()->json(['error' => 'Service not found'], 404);
        }

        return response()->json([
            'id' => $service->id,
            'name' => $service->name,
            'description' => $service->description,
            'type' => $service->type,
            'type_display' => $service->type_display,
            'price_vnd' => $service->price_vnd,
            'formatted_price' => $service->formatted_price,
            'price_per_unit' => $service->price_per_unit,
            'min' => $service->min,
            'max' => $service->max,
            'refill' => $service->refill,
            'cancel' => $service->cancel,
            'category' => $service->category->name,
        ]);
    }
}
