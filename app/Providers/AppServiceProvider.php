<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // the Supervisor role needs to be shared with the entire application
        // because it's used in the navigation menu to determine if certain links should be displayed.
        if (Schema::hasTable('roles')) {
            View::share('supervisor_role', Role::where('role', 'Supervisor')->get()->first());
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
