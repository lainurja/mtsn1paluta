<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for the admin token in the header
        $adminToken = 'admin-secret-token-12345'; // Matching the one in the controller
        $providedToken = $request->header('X-Admin-Token');

        if ($providedToken !== $adminToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access only.',
            ], 403);
        }

        return $next($request);
    }
}
