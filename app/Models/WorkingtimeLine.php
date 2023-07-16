<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingtimeLine extends Model
{
    protected $guarded = [];
    public function department(){
        return $this->hasOne('App\Models\Department','id','department_id');
    }
}
