<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as BaseModel;

/** 
* Own model will extend Laravels model 'BaseModel'.
* Any futher models can extend this model to make use of the boot functionality.
*/

class Model extends BaseModel
{
    public $incrementing = false;

    // mass assignment exception will be turned off.
    protected $guarded = [];

    protected static function boot(){
        // Calling the parent boot to boot everything.
        parent::boot();

        static::creating(function($model){
            /**
            * The primary key of $model is being overwritten by the uuid.
            * Returns an object so it needs to be casted to a string.
            */
            $model->{$model->getKeyName()} = Str::uuid();
        });
    }
}
