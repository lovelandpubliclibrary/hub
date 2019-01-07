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
        // ensure compatibility with utf8mb4 character set   
        Schema::defaultStringLength(191);

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
