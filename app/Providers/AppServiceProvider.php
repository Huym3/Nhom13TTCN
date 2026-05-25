<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <-- T thêm dòng này vào đây

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Bỏ luôn cái if, ép nó bắt buộc dùng HTTPS mọi lúc mọi nơi
        URL::forceScheme('https');
    }
}