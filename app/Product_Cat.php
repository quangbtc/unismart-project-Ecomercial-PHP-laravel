<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product_Cat extends Model
{
    //
    protected $fillable = [
        'cat_title', 'parent_id', 'slug', 'status'
    ];
    protected $table = 'product_cat';
    public function products()
    {
        return $this->hasMany('App\Product','parent_cat');
    }
    public function child()
    {
        return $this->hasMany('App\Product_Cat', 'parent_id');
    }
    
}
