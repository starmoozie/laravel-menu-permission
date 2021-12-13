<?php

namespace Starmoozie\LaravelMenuPermission\app\Models;

use Starmoozie\CRUD\app\Models\Traits\CrudTrait;
use Starmoozie\LaravelMenuPermission\app\Traits\GenerateId;

use App\Models\User as ParentUser;

class User extends ParentUser
{
    use GenerateId, CrudTrait;

    // to identity parent table name
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $new_fillable = [
        'mobile',
        'role_id',
    ];

    /**
     * New instance to append parent fillable.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable($this->new_fillable);
        parent::__construct($attributes);
    }

    public function role()
    {
        return $this->belongsTo(
            Role::class,
            'role_id',
            'id'
        );
    }
}
