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
        // Other route-specific middleware if needed.
    ];
}