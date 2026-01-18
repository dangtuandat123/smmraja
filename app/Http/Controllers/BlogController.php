<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogController extends Controller
{
    /**
     * Display list of published posts
     */
    public function index()
    {
        $posts = Post::published()
            ->latest()
            ->paginate(12);

        return view('blog.index', [
            'posts' => $posts,
            'meta' => [
                'title' => 'Blog - Tin tức & Hướng dẫn SMM',
                'description' => 'Cập nhật tin tức mới nhất về SMM, hướng dẫn tăng tương tác mạng xã hội, mẹo marketing hiệu quả.',
                'keywords' => 'blog smm, tin tức smm, hướng dẫn tăng like, hướng dẫn tăng follow, marketing mạng xã hội',
            ]
        ]);
    }

    /**
     * Display single post
     */
    public function show($slug)
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        // Increment view count
        $post->incrementViews();

        // Get related posts
        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('blog.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
