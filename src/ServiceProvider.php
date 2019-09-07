<?php

namespace Chenjianeng0201\Paygent;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Paygent::class, function () {
            return new Paygent(
                config('services.paygent.env'),
                config('services.paygent.merchant_id'),
                config('services.paygent.connect_id'),
                config('services.paygent.connect_password'),
                config('services.paygent.pem'),
                config('services.paygent.crt'),
                config('services.paygent.telegram_version'));
        });

        $this->app->alias(Paygent::class, 'paygent');
    }

    public function provides()
    {
        return [Paygent::class, 'paygent'];
    }
}
