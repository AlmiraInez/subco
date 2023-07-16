<?php

namespace App\Models;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AttendanceRevision extends Model
{
	/**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
	protected static $unguarded = true;

	public function scopeCurrentEmployee(Builder $query, $guard='api')
	{
		return $query->where('employee_id', Auth::user($guard)->employee_id);
	}

	public function attendance()
	{
	    return $this->belongsTo(Attendance::class);
	}
}
