<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role_User extends Model
{
    //
    protected $fillable = [
        'user_id','role_id'
    ];
    use SoftDeletes;
   
    
}
