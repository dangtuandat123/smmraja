<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Category;

class LandingPageController extends Controller
{
    /**
     * Landing page: Tăng Follow Instagram
     */
    public function instagram()
    {
        $category = Category::where('slug', 'like:instagram%')
            ->orWhere('name', 'like', '%instagram%')
            ->first();
        
        $services = Service::active()
            ->where('name', 'like', '%instagram%')
            ->orWhere('name', 'like', '%follow%')
            ->limit(12)
            ->get();

        return view('landing.instagram', [
            'services' => $services,
            'category' => $category,
            'meta' => [
                'title' => 'Tăng Follow Instagram Giá Rẻ - Uy Tín #1 Việt Nam',
                'description' => 'Dịch vụ tăng follow Instagram chất lượng cao, giá rẻ nhất thị trường. Follow thật, bảo hành vĩnh viễn. Tăng follow nhanh chóng trong 24h.',
                'keywords' => 'tăng follow instagram, mua follow instagram, buff follow instagram, tăng người theo dõi instagram, follow instagram giá rẻ',
            ]
        ]);
    }

    /**
     * Landing page: Mua Like Facebook
     */
    public function facebook()
    {
        $services = Service::active()
            ->where('name', 'like', '%facebook%')
            ->orWhere('name', 'like', '%fb%')
            ->limit(12)
            ->get();

        return view('landing.facebook', [
            'services' => $services,
            'meta' => [
                'title' => 'Mua Like Facebook Giá Rẻ - Tăng Like Fanpage Uy Tín',
                'description' => 'Dịch vụ mua like Facebook chất lượng cao, tăng like fanpage, like bài viết. Like thật từ người dùng Việt Nam. Bảo hành 100%.',
                'keywords' => 'mua like facebook, tăng like facebook, buff like facebook, tăng like fanpage, like facebook giá rẻ',
            ]
        ]);
    }

    /**
     * Landing page: Tăng View TikTok
     */
    public function tiktok()
    {
        $services = Service::active()
            ->where('name', 'like', '%tiktok%')
            ->orWhere('name', 'like', '%tik tok%')
            ->limit(12)
            ->get();

        return view('landing.tiktok', [
            'services' => $services,
            'meta' => [
                'title' => 'Tăng View TikTok Giá Rẻ - Mua View Video TikTok Uy Tín',
                'description' => 'Dịch vụ tăng view TikTok chất lượng cao, giá rẻ nhất. Tăng view video, tăng like, tăng follow TikTok nhanh chóng. View thật 100%.',
                'keywords' => 'tăng view tiktok, mua view tiktok, buff view tiktok, tăng like tiktok, tăng follow tiktok, view tiktok giá rẻ',
            ]
        ]);
    }

    /**
     * Landing page: Tăng View YouTube
     */
    public function youtube()
    {
        $services = Service::active()
            ->where('name', 'like', '%youtube%')
            ->orWhere('name', 'like', '%yt%')
            ->limit(12)
            ->get();

        return view('landing.youtube', [
            'services' => $services,
            'meta' => [
                'title' => 'Tăng View YouTube Giá Rẻ - Mua Subscribe YouTube Uy Tín',
                'description' => 'Dịch vụ tăng view YouTube chất lượng cao, tăng subscriber, tăng like video. View thật, giờ xem cao. Hỗ trợ kiếm tiền YouTube.',
                'keywords' => 'tăng view youtube, mua view youtube, tăng subscriber youtube, buff view youtube, tăng like youtube',
            ]
        ]);
    }

    /**
     * Landing page: SMM Panel Việt Nam
     */
    public function smmPanel()
    {
        $categories = Category::active()
            ->withCount('services')
            ->ordered()
            ->get();

        $totalServices = Service::active()->count();

        return view('landing.smm-panel', [
            'categories' => $categories,
            'totalServices' => $totalServices,
            'meta' => [
                'title' => 'SMM Panel Việt Nam #1 - Dịch Vụ Tăng Tương Tác Mạng Xã Hội',
                'description' => 'SMM Panel uy tín nhất Việt Nam. Cung cấp dịch vụ tăng like, follow, view cho Facebook, Instagram, TikTok, YouTube. Giá rẻ, chất lượng cao.',
                'keywords' => 'smm panel, smm panel việt nam, dịch vụ smm, tăng tương tác mạng xã hội, buff like, buff follow, mua like, mua view',
            ]
        ]);
    }
}
