<?php

namespace Starmoozie\LaravelMenuPermission\app\Http\Controllers;

use Starmoozie\LaravelMenuPermission\app\Http\Requests\MenuRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MenuCrudController extends CrudController
{
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ReorderOperation;

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

        CRUD::setModel(\Starmoozie\LaravelMenuPermission\app\Models\Menu::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . "/$path");
        CRUD::setEntityNameStrings(__("starmoozie::menu_permission.$heading"), __("starmoozie::menu_permission.$heading"));
        CRUD::orderBy('lft');
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

        CRUD::setValidation(MenuRequest::class);

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

    protected function setupReorderOperation()
    {
        // define which model attribute will be shown on draggable elements 
        $this->crud->set('reorder.label', 'name');
        // define how deep the admin is allowed to nest the items
        // for infinite levels, set it to 0
        $this->crud->set('reorder.max_level', 2);
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
        ->label(__('starmoozie::base.name'))
        ->size(5);

        CRUD::field('route')
        ->size(5)
        ->label(__('starmoozie::menu_permission.route'));;

        CRUD::field('icon')
        ->type('icon_picker')
        ->iconset('fontawesome')
        ->fake(true)
        ->store_in('details')
        ->size(2)
        ->label(__('starmoozie::menu_permission.icon'));

        CRUD::field('permission')
        ->type('checklist')
        ->model('Starmoozie\LaravelMenuPermission\app\Models\Permission')
        ->entity('permission')
        ->attribute('name')
        ->pivot(true)
        ->label(__('starmoozie::menu_permission.permission'));
    }
}
