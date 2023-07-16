<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KpiBot extends Model
{
    protected $guarded = [];

    public function result()
    {
        return $this->hasOne('App\Models\KpiResult', 'id', 'result_id');
    }
}
