<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class customer extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'full_name', 'address','phone','note','email'
    ];
    protected $table = 'customer';
    function orders(){
        return $this->hasMany('App\order','customer_id');
    }
}
