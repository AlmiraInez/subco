<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $guarded = [];

    // get log attendace current user auth
    public function scopeCurrentEmployee($query, $guard='api')
    {
        return $query->where('employee_id', auth()->user($guard)->employee_id);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function attendance()
    {
        return $this->hasOne('App\Models\Attendance', 'id', 'attendance_id');
    }

    public function scopeAttendanceID($query, $attendance_id)
    {
        return $query->where("attendance_id", $attendance_id);
    }
    public function scopeEmployeeID($query, $employee_id)
    {
        return $query->where("employee_id", $employee_id);
    }
    public function scopeType($query, $type)
    {
        return $query->where("type", $type);
    }
}