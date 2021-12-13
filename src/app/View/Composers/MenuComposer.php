<?php

namespace Starmoozie\LaravelMenuPermission\app\View\Composers;

use Illuminate\View\View;

use Starmoozie\LaravelMenuPermission\app\Models\Menu;

class MenuComposer
{
    public function __construct()
    {
        //
    }

    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('menu', Menu::getTree());
    }
}