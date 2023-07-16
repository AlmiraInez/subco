<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fasility extends Model
{
    protected $guarded = [];

    public function roomfasilities()
    {
        return $this->belongsTo('App\Models\Roomfasility', 'fasility_id', 'id');
    }
}
