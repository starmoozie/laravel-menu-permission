<?php

namespace Starmoozie\LaravelMenuPermission\app\Http\Controllers;

use Starmoozie\LaravelMenuPermission\app\Http\Requests\RouteRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RouteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RouteCrudController extends CrudController
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

        CRUD::setModel(\Starmoozie\LaravelMenuPermission\app\Models\Route::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . "/$path");
        CRUD::setEntityNameStrings(__("starmoozie::title.$heading"), __("starmoozie::title.$heading"));
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

        CRUD::setValidation(RouteRequest::class);

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
     * Define what happens when the Show operation is loaded.
     * 
     * @return void
     */
    protected function setupShowOperation()
    {
        $this->checkPermission();

        $this->setShows();
    }

    /**
     * Define columns in lists operation.
     * 
     * @return void
     */
    protected function setColumns()
    {
        CRUD::column('route')
        ->label(__('starmoozie::menu_permission.route_url'));

        CRUD::column('controller');

        CRUD::column('method');

        CRUD::column('type');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define form fields in create/update operation.
     * 
     * @return void
     */
    protected function setFields()
    {
        CRUD::field('route')
        ->size(6)
        ->attributes([
            'placeholder' => __('placeholder.route')
        ])
        ->label(__('starmoozie::menu_permission.route_url'));

        CRUD::field('controller')
        ->size(6)
        ->attributes([
            'placeholder' => __('placeholder.controller')
        ]);

        CRUD::field('method')
        ->type('enum')
        ->size(6);

        CRUD::field('type')
        ->type('enum')
        ->size(6);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define columns in show operation.
     * 
     * @return void
     */
    protected function setShows()
    {
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }
}
