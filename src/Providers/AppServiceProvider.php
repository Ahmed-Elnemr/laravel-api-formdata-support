<?php

namespace AhmedElnemr\LaravelApiFormdataSupport\Providers;

use Illuminate\Support\ServiceProvider;
use AhmedElnemr\LaravelApiFormdataSupport\Middleware\FormDataMiddleware;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        (new FormDataMiddleware())->handle(request(), function ($request) {});
    }
}
