<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use SoftDeletes;
     protected $fillable = [
        'name','display_name'
    ];
    function roles(){
        $this->belongsToMany('App\Role');
    }
}
