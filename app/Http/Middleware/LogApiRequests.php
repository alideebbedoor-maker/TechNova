<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequests
{
    private static $startTime;

    public function handle(Request $request, Closure $next): Response
    {
        self::$startTime = microtime(true);

        return $next($request);
    }

    public function terminate(Request $request, Response $response): void
    {
        $duration = self::$startTime 
            ? number_format((microtime(true) - self::$startTime) * 1000, 2)
            : number_format((microtime(true) - ($_SERVER['LARAVEL_START'] ?? microtime(true))) * 1000, 2);


        $user = $request->user() ? "User ID: {$request->user()->id}" : 'Guest';

        Log::info("API Request Processed and Logged (After Response)", [
            'user'       => $user,
            'method'     => $request->method(),
            'url'        => $request->fullUrl(),
            'duration'   => $duration . 'ms',
            'status'     => $response->getStatusCode(),
            'ip'         => $request->ip()
        ]);
    }
}