<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'title', 'parent_cat', 'slug', 'status', 'short_desc', 'detail', 'thumb','old_price','sale_price','inventory','view'
    ];
    protected $table = 'product';
    public function cat()
    {
        return $this->belongsTo('App\Product_Cat','parent_cat','id');
    }
    public function images(){
        return $this->hasMany('App\product_image','product_id');
    }
    function orders(){
        return $this->belongsToMany('App\order', 'order_item', 'product_id', 'order_id');
    }
}
