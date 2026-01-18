<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use App\Models\Post;
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
        $posts = Post::published()->get();

        $content = view('sitemap', compact('categories', 'services', 'posts'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
