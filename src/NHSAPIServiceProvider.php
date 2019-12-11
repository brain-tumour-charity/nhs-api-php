<?php 

namespace NHSAPI;

use Illuminate\Support\ServiceProvider;

class NHSAPIServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/config/config.php'      => config_path('nhs_api.php')], 'nhs_api');
        $this->publishes([__DIR__ . '/storage/conditions.csv' => storage_path('conditions.csv')], 'nhs_api_conditions');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'nhs_api');

        $this->app->singleton('brain-tumour-charity.nhs-api-php', function ($app) {
            return new Client(
                $app['config']->get('nhs_api.trial_key'),
                $app['config']->get('nhs_api.cache_expiry')
            );
        });
    }

    /**
     * Get the services provided by the provider.
    *
    * @return array
    */
    public function provides()
    {
        return ['brain-tumour-charity.nhs-api-php'];
    }

}
