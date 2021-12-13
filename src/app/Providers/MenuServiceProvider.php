<?php

namespace Starmoozie\LaravelMenuPermission\app\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use Starmoozie\LaravelMenuPermission\app\View\Composers\MenuComposer;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        View::composer(
            starmoozie_view('inc.sidebar_content'),
            MenuComposer::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
