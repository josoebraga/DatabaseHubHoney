<?php

namespace App\Providers;

use App\Models\Teste;
use App\Observers\TesteObserver;

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
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set('America/Sao_Paulo');
        Teste::observe(TesteObserver::class);
    }
}
