<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log; // Added for logging

class DebugCookieMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Process the request and get the response
        $response = $next($request);

        // --- Debugging for XSRF-TOKEN Cookie ---
        // This will dump the response headers and stop execution, allowing you to see the cookies.
        // Look specifically for the 'Set-Cookie' header that contains 'XSRF-TOKEN'.
        // Check its attributes, especially 'httponly'.

        // Check if it's a response that would typically set cookies
        if ($response instanceof \Illuminate\Http\JsonResponse || $response instanceof \Illuminate\Http\Response) {
            $setCookieHeaders = $response->headers->get('Set-Cookie', null, false); // Get all Set-Cookie headers

            if (!empty($setCookieHeaders)) {
                Log::debug('--- DebugCookieMiddleware: Set-Cookie Headers ---');
                foreach ($setCookieHeaders as $cookieString) {
                    Log::debug('Cookie String: ' . $cookieString);
                    if (str_contains($cookieString, 'XSRF-TOKEN')) {
                        Log::debug('XSRF-TOKEN found: ' . $cookieString);
                        // You can use dd() here if you want to stop execution and see it immediately in the browser
                        // dd('XSRF-TOKEN Cookie String:', $cookieString);
                    }
                }
                Log::debug('----------------------------------------------------');
            } else {
                Log::debug('--- DebugCookieMiddleware: No Set-Cookie headers found. ---');
            }
        } else {
            Log::debug('--- DebugCookieMiddleware: Response is not JsonResponse or Response. ---');
        }

        return $response;
    }
}