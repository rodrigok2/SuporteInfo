<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use App\Models\User;

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
        //utilizar o bootstrap na paginação
        Paginator::useBootstrap();

        //Cria permissão para acesso somente do usuario 102 (Rodrigo Martins)
        Gate::define('administrador', function (User $user){
            return $user->id == 102;
        });
    }
}
