<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_item extends Model
{
    //
    protected $fillable = [
        'product_id', 'order_id','qty','status','sub_total'
    ];
    protected $table = 'order_item';
  

}
