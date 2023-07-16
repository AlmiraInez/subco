<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryReport extends Model
{
    protected $guarded = [];
    public function salarydetail()
    {
        return $this->hasMany('App\Models\SalaryReportDetail', 'salary_report_id', 'id');
    }
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentEmployee($query, $guard)
    {
        return $query->where('employee_id', auth()->user($guard)->employee_id);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentPeriod($query)
    {
        return $query->where('period', date('Y-m-01'));
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAnotherPeriod($query)
    {
        return $query->where('period', '<', date('Y-m-01'));
    }
}
