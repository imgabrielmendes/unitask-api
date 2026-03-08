<?php

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // ... outras propriedades aqui

    protected $middlewareGroups = [
        'web' => [
            // middlewares do grupo "web"
        ],

        'api' => [
            \Illuminate\Http\Middleware\HandleCors::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    // ... outras funções aqui
}

