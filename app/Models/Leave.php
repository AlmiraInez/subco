<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    protected $guarded = [];
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }
    public function leavesetting()
    {
        return $this->belongsTo('App\Models\LeaveSetting', 'leave_setting_id');
    }
    public function log()
    {
        return $this->hasMany('App\Models\LeaveLog', 'leave_id');
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentEmployee($query, $guard = 'api')
    {
        return $query->where('employee_id', auth()->user($guard)->employee_id);
    }
}