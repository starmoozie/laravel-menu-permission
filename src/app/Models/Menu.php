<?php

namespace Starmoozie\LaravelMenuPermission\app\Models;

use Illuminate\Database\Eloquent\Model;
use Starmoozie\CRUD\app\Models\Traits\CrudTrait;

use Starmoozie\LaravelMenuPermission\app\Traits\GenerateId;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class Menu extends Model
{
    use CrudTrait, GenerateId, Cachable;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table    = 'menu';
    protected $guarded  = ['id'];
    protected $fillable = [
        'id',
        'name',
        'route',
        'lft',
        'rgt',
        'depth',
        'details',
        'controller',
    ];
    protected $casts    = [
        'details' => 'array'
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get all menu items, in a hierarchical collection.
     * Only supports 2 levels of indentation.
     */
    public static function getTree()
    {
        $role = starmoozie_user()->role;

        $menu = Self::when($role && $role->menuPermission, fn($q) => $q->whereIn(
            'id',
            $role->menuPermission
            ->pluck('menu_id')
            ->toArray()
        ))
        ->orderBy('lft')
        ->get();

        if ($menu->count()) {
            foreach ($menu as $k => $menu_item) {
                $menu_item->children = collect([]);

                foreach ($menu as $i => $menu_subitem) {
                    if ($menu_subitem->parent_id == $menu_item->id) {
                        $menu_item->children->push($menu_subitem);

                        // remove the subitem for the first level
                        $menu = $menu->reject(function ($item) use ($menu_subitem) {
                            return $item->id == $menu_subitem->id;
                        });
                    }
                }
            }
        }

        return $menu;
    }

    public function listType()
    {
        return $this->parent_id ? 'Children' : 'Parent';
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function permission()
    {
        return $this->belongsToMany(
            Permission::class
        )
        ->using(MenuPermission::class)
        ->withPivot([
            'id'
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getIconAttribute()
    {
        $details = $this->details;

        return $details && is_array($details) && isset($details['icon']) ? $details['icon'] : null;
    }

    public function getIsParentAttribute()
    {
        return $this->route === '#' ? '1' : '0';
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }
}
