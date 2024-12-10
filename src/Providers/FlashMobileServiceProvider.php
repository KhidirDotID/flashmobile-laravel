<?php

namespace KhidirDotID\FlashMobile\Providers;

use Illuminate\Support\ServiceProvider;
use KhidirDotID\FlashMobile\FlashMobile;

class FlashMobileServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'flashmobile');

        $this->app->singleton('flashmobile', function ($app) {
            return new FlashMobile($app);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->publishes([
            $this->getConfigPath() => config_path('flashmobile.php'),
        ], 'flashmobile-config');
    }

    public function getConfigPath()
    {
        return __DIR__ . '/../../config/flashmobile.php';
    }
}
