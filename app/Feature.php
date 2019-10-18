<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    protected $fillable = [
        'feature_name', 'feature_description'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Product');
    }

    //
}
