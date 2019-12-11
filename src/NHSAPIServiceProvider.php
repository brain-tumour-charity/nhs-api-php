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
        $this->publishes([__DIR__ . '/config/config.php' => config_path('nhs_api.php')], 'nhs_api');
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
                $app['config']->get('nhs_api.trial_key')
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
