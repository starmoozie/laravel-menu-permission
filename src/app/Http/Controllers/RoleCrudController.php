<?php

namespace Starmoozie\LaravelMenuPermission\app\Http\Controllers;

use Starmoozie\LaravelMenuPermission\app\Http\Requests\RoleRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RoleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RoleCrudController extends CrudController
{
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Starmoozie\LaravelMenuPermission\app\Traits\CheckPermission;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        $path = request()->segment(2);
        $heading = str_replace('-', ' ', $path);

        CRUD::setModel(\Starmoozie\LaravelMenuPermission\app\Models\Role::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . "/$path");
        CRUD::setEntityNameStrings(__("starmoozie::title.$heading"), __("starmoozie::title.$heading"));
        CRUD::orderBy('name');

        if (!is_me(starmoozie_user()->email)) {
            CRUD::addClause('where', 'name', '!=', 'developer');
        }
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        $this->checkPermission();

        if (!is_me(starmoozie_user()->email)) {
            CRUD::denyAccess(['create', 'update', 'delete']);
        }

        $this->setColumns();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->checkPermission();

        CRUD::setValidation(RoleRequest::class);

        $this->setFields();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define list columns.
     * 
     * @return void
     */
    private function setColumns()
    {
        CRUD::column('name')
        ->label(__('starmoozie::base.name'));
    }

    private function setFields()
    {
        CRUD::field('name')
        ->label(__('starmoozie::base.name'));

        CRUD::field('menuPermission')
        ->label(__('starmoozie::menu_permission.menu_permission'))
        ->type('menu_permission')
        ->model('Starmoozie\LaravelMenuPermission\app\Models\MenuPermission')
        ->entity('menuPermission')
        ->attribute('child')
        ->pivot(true)
        ->view_namespace('menu_permission_view::fields')
        ->options(fn($query) => $query->joinMenuPermission()->get([
            'p.name as child',
            'menu_permission.id',
            'm.name as parent'
        ]));
    }
}
