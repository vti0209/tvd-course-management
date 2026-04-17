<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AdminMiddleware
{
   public function handle(Request $request, Closure $next): Response
{
    // Check admin guard
    if (!Auth::guard('admin')->check()) {
        abort(403, 'Unauthorized');
    }

    return $next($request);
}
}