<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['available_amount'];

    public function users()
    {
        return $this->belongsToMany('App\Users');
    }
}
