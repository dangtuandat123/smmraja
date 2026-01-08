<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Setting;

class CheckMaintenance
{
    /**
     * Routes that should be accessible during maintenance
     */
    protected array $except = [
        'login',
        'admin/*',
        'api/*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if maintenance mode is enabled
        if ($this->isMaintenanceMode()) {
            // Allow admin users
            if (auth()->check() && auth()->user()->isAdmin()) {
                return $next($request);
            }

            // Allow excepted routes
            foreach ($this->except as $pattern) {
                if ($request->is($pattern)) {
                    return $next($request);
                }
            }

            // Return maintenance page
            return response()->view('errors.503', [], 503);
        }

        return $next($request);
    }

    /**
     * Check if maintenance mode is enabled from settings
     */
    protected function isMaintenanceMode(): bool
    {
        try {
            $value = Setting::get('maintenance_mode');
            return filter_var($value, FILTER_VALIDATE_BOOLEAN);
        } catch (\Exception $e) {
            return false;
        }
    }
}
