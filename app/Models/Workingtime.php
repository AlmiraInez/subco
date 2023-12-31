<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workingtime extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany('App\Models\WorkingtimeDetail', 'workingtime_id', 'id');
    }
    public function workingtimeallowance()
    {
        return $this->hasMany('App\Models\WorkingtimeAllowance', 'workingtime_id', 'id');
    }
    public function workingtimeline()
    {
        return $this->hasMany('App\Models\WorkingtimeLine', 'workingtime_id', 'id');
    }
}