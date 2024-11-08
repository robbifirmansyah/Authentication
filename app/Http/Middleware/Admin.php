<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Pastikan pengguna terautentikasi
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah user adalah admin
        if (auth()->user()->level !== 'admin') {
            // Jika bukan admin, redirect ke halaman welcome dengan pesan error
            return redirect()->route('welcome')
                ->with('error', 'Anda bukan admin.');
        }

        // Lanjutkan ke halaman tujuan jika user adalah admin
        return $next($request);
    }
}
