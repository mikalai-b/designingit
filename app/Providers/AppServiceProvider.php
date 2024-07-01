<?php

namespace App\Providers;

use Omnipay\Omnipay;
use Omnipay\Common\GatewayInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GatewayInterface::class, function($app) {
            $gateway = Omnipay::create('AuthorizeNet_CIM');

            $gateway->setApiLoginId(getenv('AUTHNET_LOGIN'));
            $gateway->setTransactionKey(getenv('AUTHNET_KEY'));

            if (getenv('AUTHNET_TEST_MODE')) {
                $gateway->setDeveloperMode(TRUE);
            }

            return $gateway;
        });
    }
}
