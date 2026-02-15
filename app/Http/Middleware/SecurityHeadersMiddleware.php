<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Security Headers Middleware
 *
 * Menambahkan security headers ke semua HTTP responses untuk melindungi
 * aplikasi dari berbagai jenis serangan web.
 *
 * Security headers yang ditambahkan:
 * - X-Frame-Options: Mencegah clickjacking
 * - X-Content-Type-Options: Mencegah MIME sniffing
 * - X-XSS-Protection: Mengaktifkan XSS filter browser
 * - Strict-Transport-Security: Memaksa HTTPS (hanya production)
 * - Content-Security-Policy: Mencegah XSS, clickjacking, dll
 * - Referrer-Policy: Mengontrol informasi referrer
 * - Permissions-Policy: Mengontrol fitur browser yang boleh digunakan
 */
class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // X-Frame-Options: DENY
        // Mencegah website di-embed di iframe (clickjacking protection)
        // DENY = Tidak boleh di-embed sama sekali (paling secure)
        // SAMEORIGIN = Hanya boleh di-embed dari origin yang sama
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options: nosniff
        // Mencegah browser dari "sniffing" response content-type
        // Melindungi dari MIME-type confusion attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection: 1; mode=block
        // Mengaktifkan XSS filter built-in browser
        // Mode = block akan memblokir page jika serangan terdeteksi
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy: strict-origin-when-cross-origin
        // Mengontrol berapa banyak informasi referrer yang dikirim ke destination
        // - full URL untuk same-origin requests
        // - hanya origin (scheme, host, port) untuk cross-origin requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy
        // Mengontrol fitur browser/device APIs yang boleh digunakan
        // Memblokir akses ke geolocation, camera, microphone, dll
        $response->headers->set('Permissions-Policy', 'geolocation=(), payment=(), camera=(), microphone=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()');

        // Strict-Transport-Security (HSTS)
        // Hanya aktif di production dan saat request melalui HTTPS
        // Memaksa browser untuk selalu menggunakan HTTPS
        // max-age=31536000 = 1 tahun
        // includeSubDomains = Berlaku untuk semua subdomains
        // preload = Memungkinkan ditambahkan ke HSTS preload list
        if (app()->environment('production') && $request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        // Content-Security-Policy (CSP)
        // Header keamanan paling penting - mencegah berbagai jenis serangan:
        // - XSS (Cross-Site Scripting)
        // - Clickjacking
        // - Code injection
        // - Mixed content

        // Untuk local development, CSP di-disabled untuk menghindari konflik dengan Vite
        // yang menggunakan IPv6 ([::1]) dan dynamic ports
        if (app()->environment('local')) {
            // Development: CSP Disabled
            // Di local development, CSP sering terjadi konflik dengan:
            // - Vite dev server yang menggunakan IPv6 ([::1]:5173)
            // - Dynamic port numbers
            // - Hot module replacement (HMR)
            // Untuk development, security lebih diutamakan dengan cara lain (firewall, VPN, dll)
            // CSP akan aktif kembali di production
        } else {
            // Production CSP - strict dan secure
            $csp = "default-src 'self'; "
                . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net https://unpkg.com; "
                . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://unpkg.com https://fonts.bunny.net; "
                . "img-src 'self' data: https:; "
                . "font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net https://unpkg.com https://fonts.bunny.net; "
                . "connect-src 'self' https://www.google.com https://cdn.jsdelivr.net; "
                . "frame-src https://www.google.com; "
                . "frame-ancestors 'none';";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
