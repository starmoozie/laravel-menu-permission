<?php

namespace Starmoozie\LaravelMenuPermission\app\Traits;

/**
 * 
 */
trait GenerateId
{
    /**
      * Boot function from Laravel.
    **/
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $unique = filter_var(microtime(true), FILTER_SANITIZE_NUMBER_INT);
                $model->{$model->getKeyName()} = substr($unique, 0, 14);
            }
        });
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
    **/
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
    *
    * @return string
    **/
    public function getKeyType()
    {
        return 'string';
    }
}
