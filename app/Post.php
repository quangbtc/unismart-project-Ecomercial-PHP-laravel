<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title', 'parent_cat','slug', 'status','short_desc','content','thumb'
    ]; 
    protected $table='posts';
    public function cat()
    {
        return $this->belongsTo('App\Post_Cat','parent_cat');
    }
}
