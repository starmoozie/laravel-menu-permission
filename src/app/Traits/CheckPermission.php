<?php

namespace Starmoozie\LaravelMenuPermission\app\Traits;

/**
 * 
 */
trait CheckPermission
{
    private $default_access = [
        'list', 
        'create', 
        'delete', 
        'update', 
        'show',
        'print'
    ];

    public function checkPermission()
    {
        $menu_permission = $this->getMenuPermission();

        // If user doesn't have permission show, delete, update in button line
        // Then remove action column in the table list
        if (empty(array_intersect(['show', 'delete', 'update', 'print'], $menu_permission))) {
            $this->crud->removeAllButtonsFromStack('line');
        }

        // If user has export permission, then show export button in list view
        if (in_array('export', $menu_permission)) {
            $this->crud->enableExportButtons();
        }

        // if user has permission permission & in model fillable has user_id, then add query where userid = current userid
        if (in_array('personal', $menu_permission) && in_array('created_by', $this->crud->model->getFillable())) {
            $this->crud->addClause('whereCreatedBy', starmoozie_user()->id);
        }

        // allowed default access in current route
        $this->crud->allowAccess($menu_permission);
    }

    private function getMenuPermission()
    {
        $this->crud->denyAccess($this->default_access); // Deny access all permission in current route

        $route = explode('/', $this->crud->getRoute()); // Get current url as array
        $role  = starmoozie_user()->role; // Get user menu_permission

        if ($role) {
            $permission = $role->menuPermission;
            $permission = $permission->load(['menu', 'permission']); // Then load menu, permission from related entry
            $permission = $permission->map(function($q) { // Mapping menu url and permission name
                $permission_name = strtolower($q->permission->name);
                $permission_name = $permission_name === 'read' ? 'list' : $permission_name;
    
                $data[strtolower($q->menu->route)] = $permission_name;
    
                return $data;
            });
        }

        // Get user permission in current route
        return isset($permission) ? array_column($permission->toArray(), end($route)) : [];
    }
}
