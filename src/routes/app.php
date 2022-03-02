<?php

Route::group([
    'prefix'     => config('starmoozie.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('starmoozie.base.web_middleware', 'web'),
        (array) config('starmoozie.base.middleware_key', 'admin')
    ),
    'namespace'  => 'Starmoozie\LaravelMenuPermission\app\Http\Controllers',
], function () {
    if (config('starmoozie.base.setup_permission_url')) {
        Route::crud('permission', 'PermissionCrudController');
    }
    if (config('starmoozie.base.setup_menu_url')) {
        Route::crud('menu', 'MenuCrudController');
    }
    if (config('starmoozie.base.setup_role_url')) {
        Route::crud('role', 'RoleCrudController');
    }
    if (config('starmoozie.base.setup_user_url')) {
        Route::crud('user', 'UserCrudController');
    }
    if (config('starmoozie.base.setup_route_url')) {
        Route::crud('route', 'RouteCrudController');
    }
});