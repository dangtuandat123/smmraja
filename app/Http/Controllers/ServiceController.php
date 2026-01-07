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

        $categories = Category::active()
            ->ordered()
            ->withCount(['services' => function ($query) {
                $query->where('is_active', true);
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

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $servicesQuery->orderBy('price_vnd', 'asc');
                break;
            case 'price_desc':
                $servicesQuery->orderBy('price_vnd', 'desc');
                break;
            case 'name_asc':
                $servicesQuery->orderBy('name', 'asc');
                break;
            case 'newest':
                $servicesQuery->orderBy('created_at', 'desc');
                break;
            default:
                $servicesQuery->ordered();
                break;
        }

        $services = $servicesQuery->paginate(20)->appends($request->query());

        return view('services.index', compact('categories', 'services', 'categorySlug', 'search', 'sort'));
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
