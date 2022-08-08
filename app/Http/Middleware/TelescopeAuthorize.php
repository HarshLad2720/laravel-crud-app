<?php

namespace App\Http\Middleware;

class TelescopeAuthorize
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return app()->environment('local') ||
        in_array($request->ip(), explode(',', config('constants.allowed_ip_addresses.telescope'))) ? $next($request) : abort(403);
    }
}
