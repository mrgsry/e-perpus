<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan memiliki role 'admin'
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('admin.login')
                            ->with('error', 'Silakan login sebagai administrator terlebih dahulu.');
        }
        return $next($request);
    }
}
