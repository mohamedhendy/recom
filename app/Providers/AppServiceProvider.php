<?php

namespace App\Providers;

use easybill\SDK\Client;
use easybill\SDK\Endpoint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment(config('telescope.active_environments'))) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        //
        app()->singleton(
            'EasyBill',
            function () {
                return new Client(new Endpoint(config('services.easy_bill.api_key')));
            }
        );

        Blade::directive('money', function ($money) {
            return "<?php echo number_format($money, 2); ?>";
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
