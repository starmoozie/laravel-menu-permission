<?php

namespace Starmoozie\LaravelMenuPermission\database\seeders;

use Illuminate\Database\Seeder;

use Starmoozie\LaravelMenuPermission\app\Models\Permission;
use Starmoozie\LaravelMenuPermission\app\Models\Menu;
use Starmoozie\LaravelMenuPermission\app\Models\MenuPermission;
use Starmoozie\LaravelMenuPermission\app\Models\Role;
use Starmoozie\LaravelMenuPermission\app\Models\User;

class LaravelMenuPermissionSeeder extends Seeder
{
    private $permission = [
        [
            'name' => 'create'
        ],
        [
            'name' => 'read'
        ],
        [
            'name' => 'update'
        ],
        [
            'name' => 'delete'
        ],
        [
            'name' => 'show'
        ],
        [
            'name' => 'personal'
        ],
        [
            'name' => 'print'
        ],
        [
            'name' => 'export'
        ],
    ];

    private $menu = [
        [
            'name'  => 'menu',
            'route' => 'menu'
        ],
        [
            'name'  => 'permission',
            'route' => 'permission',
        ],
        [
            'name'  => 'role',
            'route' => 'role'
        ],
        [
            'name'  => 'user',
            'route' => 'user',
        ],
    ];

    private $role = [
        [
            'name'  => 'developer'
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->permission as $key => $value) {
            Permission::updateOrCreate([
                'name' => $value['name']
            ], $value);
        }

        foreach ($this->menu as $key => $value) {
            Menu::updateOrCreate([
                'name' => $value['name']
            ], $value);
        }

        $permission = Permission::pluck('id')->toArray();

        Menu::all()->each(function($menu) use ($permission) {
            $menu->permission()->sync($permission);
        });

        $menu_permission = MenuPermission::pluck('id')->toJson();

        foreach ($this->role as $key => $value) {

            $check = Role::whereName($value['name'])->first();

            if ($check) {
                \DB::table('role')->update([
                    'name'    => $value['name'],
                    'options' => $menu_permission
                ]);
            }
            else {
                $unique         = filter_var(microtime(true), FILTER_SANITIZE_NUMBER_INT);
                \DB::table('role')->insert([
                    'id'      => substr($unique, 0, 14),
                    'name'    => $value['name'],
                    'options' => $menu_permission
                ]);
            }

            User::updateOrCreate([
                'email' => 'starmoozie@gmail.com'
            ], [
                'name'     => 'starmoozie',
                'email'    => 'starmoozie@gmail.com',
                'mobile'   => '085746400500',
                'password' => \Hash::make('password'),
                'role_id'  => Role::whereName($value['name'])->first()->id
            ]);
        }
    }
}
