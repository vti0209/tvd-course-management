<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictAdminProviderFromUserPages
{
    public function handle(Request $request, Closure $next)
    {
        // If admin is logged in
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard');
        }
        
        // If provider is logged in
        if (Auth::guard('provider')->check()) {
            return redirect()->route('provider.dashboard');
        }

        // Allow web users or guests
        return $next($request);
    }
}