<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;

use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\CreatesNewUsers;


class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

    }

    public function boot()
    {
        Fortify::loginView(fn() => view('auth.login'));

        Fortify::registerView(fn() => view('auth.register'));

        Fortify::verifyEmailView(fn() => view('auth.verify'));
    }
}