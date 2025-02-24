<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // ... existing code ...
        'auth.jwt' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
        'jwt.verify' => \App\Http\Middleware\JWTMiddleware::class,
    ];
} 