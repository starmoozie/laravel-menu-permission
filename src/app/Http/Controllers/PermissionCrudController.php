<?php

namespace Starmoozie\LaravelMenuPermission\app\Http\Controllers;

use Starmoozie\LaravelMenuPermission\app\Http\Requests\PermissionRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PermissionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PermissionCrudController extends CrudController
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
        $heading = str_replace('-', ' ', $segment);

        CRUD::setModel(\Starmoozie\LaravelMenuPermission\app\Models\Permission::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . "/$path");
        CRUD::setEntityNameStrings(__("label.$heading"), __("label.$heading"));
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        $this->checkPermission();

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

        CRUD::setValidation(PermissionRequest::class);

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

    /**
     * Define create / update form fields.
     * 
     * @return void
     */
    private function setFields()
    {
        CRUD::field('name')
        ->label(__('starmoozie::base.name'));
    }
}
