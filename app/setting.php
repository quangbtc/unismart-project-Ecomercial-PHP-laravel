<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    //
    protected $fillable=['config','value'];
    protected $table='setting';

}
