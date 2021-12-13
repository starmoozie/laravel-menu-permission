<?php

namespace Starmoozie\LaravelMenuPermission\app\Http\Controllers;

use Starmoozie\LaravelMenuPermission\app\Http\Requests\UserRequest;
use Starmoozie\CRUD\app\Http\Controllers\CrudController;
use Starmoozie\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Starmoozie\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Starmoozie\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
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
        CRUD::setModel(\Starmoozie\LaravelMenuPermission\app\Models\User::class);
        CRUD::setRoute(config('starmoozie.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'user');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @return void
     */
    protected function setupListOperation()
    {
        $this->checkPermission();

        CRUD::column('name')
        ->label(__('starmoozie::base.name'));

        CRUD::column('email');

        CRUD::column('role_id');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->checkPermission();

        CRUD::setValidation(UserRequest::class);

        $this->setFields();
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        CRUD::setRequest(CRUD::validateRequest());
        CRUD::setRequest($this->handlePasswordInput(CRUD::getRequest()));
        CRUD::unsetValidation();

        return $this->traitStore();
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
     * Update the specified resource in the database.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update()
    {
        CRUD::setRequest(CRUD::validateRequest());
        CRUD::setRequest($this->handlePasswordInput(CRUD::getRequest()));
        CRUD::unsetValidation();

        return $this->traitUpdate();
    }

    /**
     * Handle password input fields.
     */
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', \Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

    private function setFields()
    {
        CRUD::field('name')
        ->label(__('starmoozie::base.name'))
        ->size('6');

        CRUD::field('email')
        ->size('6');

        CRUD::field('mobile')
        ->size('6');

        CRUD::field('role')
        ->relationship('select2')
        ->size('6')
        ->allows_null(false);

        CRUD::field('password')
        ->type('password')
        ->size('6');

        CRUD::field('password_confirmation')
        ->type('password')
        ->size('6');
    }
}
