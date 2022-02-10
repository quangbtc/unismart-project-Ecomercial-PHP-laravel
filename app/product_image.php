<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_image extends Model
{
    //
   
    protected $fillable = [
        'image','product_id'
    ];
    protected $table = 'product_image';
    public function products()
    {
        return $this->belongsTo('App\Product', 'product_id');
    }
}
