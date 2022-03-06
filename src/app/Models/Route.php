<?php

namespace Starmoozie\LaravelMenuPermission\app\Models;

use Illuminate\Database\Eloquent\Model;
use Starmoozie\CRUD\app\Models\Traits\CrudTrait;

class Route extends Model
{
    /*
    |--------------------------------------------------------------------------
    | TRAITS
    |--------------------------------------------------------------------------
    */

    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table    = 'route';
    protected $fillable = [
        'route',
        'type',
        'alias',
        'method',
        'controller',
        'middleware',
    ];
    protected $casts    = [
        'middleware' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeDashboard($query)
    {
        return $query->whereIn('type', ['dashboard', 'dashboard_api']);
    }

    public function scopeDashboardApi($query)
    {
        return $query->whereType('dashboard_api');
    }

    public function scopeApi($query)
    {
        return $query->whereType('api');
    }

    public function scopeWeb($query)
    {
        return $query->whereType('web');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
