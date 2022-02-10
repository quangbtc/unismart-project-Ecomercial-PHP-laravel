<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class slider extends Model
{
    //
    protected $fillable = [
        'name', 'image_name', 'image'
    ];
    protected $table = 'slider';
}
