<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveDetail extends Model
{
    protected $guarded = [];
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }
    public function leavesetting()
    {
        return $this->belongsTo('App\Models\LeaveSetting', 'leavesetting_id');
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePeriod($query)
    {
        return $query->where('from_balance', '<=', date('Y-m-d'))->where('to_balance', '>=', date('Y-m-d'));
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentEmployee($query, $guard = 'api')
    {
        return $query->where('employee_id', auth()->guard($guard)->user()->employee_id);
    }
}