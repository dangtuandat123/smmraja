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

        $categories = Category::active()
            ->ordered()
            ->withCount(['services' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get();

        $servicesQuery = Service::active()
            ->with('category')
            ->ordered();

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

        $services = $servicesQuery->paginate(20);

        return view('services.index', compact('categories', 'services', 'categorySlug', 'search'));
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
