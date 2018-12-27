<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\Relation;
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
        // because it's used in the navigation menu to determine if certain
        // links should be displayed.
        /* This needs to be commented out for php artisan optimize and/or a fresh database to run */
        /* start comment here */
        if (Schema::hasTable('roles')) {
            View::share('supervisor_role', Role::where('role', 'Supervisor')->get()->first());
        }

        Schema::defaultStringLength(191);
        /* end comment here */


        // store polymorphic relationships without using fully qualified classname
        Relation::morphMap([
            'incident' => 'App\Incident',
            'patron' => 'App\Patron',
            'photo' => 'App\Photo',
        ]);
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
