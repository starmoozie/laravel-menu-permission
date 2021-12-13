<?php

namespace Starmoozie\LaravelMenuPermission\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;

use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use Starmoozie\LaravelMenuPermission\app\Traits\GenerateId;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class MenuPermission extends Pivot
{
    use HasFactory, GenerateId, HasJsonRelationships, Cachable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table    = 'menu_permission';
    protected $guarded  = ['id'];
    protected $fillable = [
        'id',
        'menu_id',
        'permission_id'
    ];

    public $timestamps  = false;

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

    public function menu()
    {
        return $this->belongsTo(
            Menu::class,
            'menu_id',
            'id'
        );
    }

    public function permission()
    {
        return $this->belongsTo(
            Permission::class,
            'permission_id',
            'id'
        );
    }

    public function role()
    {
        $this->hasManyJson(MenuPermission::class, 'menu_permission[]->id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeJoinMenuPermission($query)
    {
        return $query->join('menu as m', 'm.id', 'menu_permission.menu_id')
        ->join('permission as p', 'p.id', 'menu_permission.permission_id')
        ->orderBy('m.lft');
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
