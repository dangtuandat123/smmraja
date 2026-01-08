<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageView;

class TrackPageViews
{
    /**
     * Paths to exclude from tracking
     */
    protected array $except = [
        'admin/*',
        'api/*',
        'livewire/*',
        '_debugbar/*',
        'sanctum/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful GET requests
        if ($request->isMethod('GET') && $response->isSuccessful()) {
            if (!$this->shouldExclude($request)) {
                try {
                    PageView::track(
                        $request->path(),
                        auth()->id()
                    );
                } catch (\Exception $e) {
                    // Silently fail - don't break the app
                }
            }
        }

        return $response;
    }

    /**
     * Check if the path should be excluded
     */
    protected function shouldExclude(Request $request): bool
    {
        foreach ($this->except as $pattern) {
            if ($request->is($pattern)) {
                return true;
            }
        }

        return false;
    }
}
