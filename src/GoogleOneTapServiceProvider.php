<?php

namespace Scriptoshi\LivewireGoogleOneTap;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class GoogleOneTapServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the config
        $this->mergeConfigFrom(
            __DIR__ . '/../config/google-onetap.php',
            'google-onetap'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__ . '/../config/google-onetap.php' => config_path('google-onetap.php'),
        ], 'google-onetap-config');

        // Register views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'google-onetap');

        // Publish views
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/google-onetap'),
        ], 'google-onetap-views');

        // Register migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Publish migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'google-onetap-migrations');

        // Register Livewire component
        Livewire::component('google-onetap', GoogleOneTap::class);
    }
}
