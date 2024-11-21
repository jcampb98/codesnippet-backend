<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Middleware\HandleCors;

class Kernel extends HttpKernel
{
    /**
     * The applications global HTTP middleware stack.
     * 
     * @var array
     */
    protected $middleware = [
        HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth:api' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,  // for PHP Open Source Saver JWT package
        'jwt.auth' => \PhpOpenSourceSaver\JWTAuth\Http\Middleware\Authenticate::class,
        'jwt.refresh' => \PhpOpenSourceSaver\JWTAuth\Http\Middleware\RefreshToken::class,
    ];
}