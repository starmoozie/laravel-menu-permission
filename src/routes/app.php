<?php

Route::group([
    'prefix'     => config('starmoozie.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('starmoozie.base.web_middleware', 'web'),
        (array) config('starmoozie.base.middleware_key', 'admin')
    ),
    'namespace'  => 'Starmoozie\LaravelMenuPermission\app\Http\Controllers',
], function () {
    if (config('starmoozie.base.setup_permission_route')) {
        Route::crud('permission', 'PermissionCrudController');
    }
    if (config('starmoozie.base.setup_menu_route')) {
        Route::crud('menu', 'MenuCrudController');
    }
    if (config('starmoozie.base.setup_role_route')) {
        Route::crud('role', 'RoleCrudController');
    }
    if (config('starmoozie.base.setup_user_route')) {
        Route::crud('user', 'UserCrudController');
    }
});