<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah session token ada dan waktu sesi belum kedaluwarsa
        if (!session('access_token') || session('login_time')->addSeconds(session('expires_in')) < now()) {
            // Hapus semua session jika token tidak ada atau sudah expired
            $request->session()->flush();

            // Arahkan ke halaman login
            return redirect()->route('auth.login.form')->withErrors('Sesi telah kedaluwarsa. Silakan login kembali.');
        }

        return $next($request);
    }
}
