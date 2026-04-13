<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // not logged in → go to login
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        // logged in but NOT admin → block
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Not admin');
        }

        return $next($request);
    }
}
