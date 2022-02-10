<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class page extends Model
{
    //
    use SoftDeletes;
    protected $fillable=['title','content','slug','status'];
    protected $table = 'pages';

}
