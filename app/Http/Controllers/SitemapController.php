<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate sitemap.xml
     */
    public function index(): Response
    {
        $categories = Category::active()->ordered()->get();
        $services = Service::active()->with('category')->get();

        $content = view('sitemap', compact('categories', 'services'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
