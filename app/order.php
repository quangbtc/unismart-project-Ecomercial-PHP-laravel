<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $fillable = [
        'customer_id', 'status','total','payment','note','code'
    ];
    protected $table = 'order';
    function customer()
    {
        return $this->belongsTo('App\customer','customer_id');
    }
    function products(){
        return $this->belongsToMany('App\Product', 'order_item', 'order_id', 'product_id')->withPivot('price','qty','sub_total','created_at','updated_at');
    }
}

