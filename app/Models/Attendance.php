<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AttendanceLog;

class Attendance extends Model
{
    protected $guarded = [];

    /**
     * Scope Method
     **/
    public function scopeCurrentEmployee($query, $guard='api')
    {
        return $query->where('employee_id', auth()->user($guard)->employee_id);
    }

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function getStatusAttribute()
    {

        if ($this->ensureIsHoliday()) {
            return "Libur";
        } elseif ($this->ensureIsNotPressent()) {

            return $this->checkEmployeeAreWorking();

        } elseif ($this->ensureIsLeave()) {
            return "Cuti";
        }
        return "Masuk";
    }

    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }
    public function workingtime()
    {
        return $this->belongsTo('App\Models\Workingtime', 'workingtime_id', 'id');
    }
    public function leave()
    {
        return $this->hasMany('App\Models\LeaveLog', 'reference_id');
    }
    // public function schemalist()
    // {
    //     return $this->belongsTo('App\Models\OvertimeScheme', 'overtime_scheme_id');
    // }
    public function scopeEmployeeAttendance($query, $employee_id)
    {
        return $query->where('employee_id', $employee_id);
    }
    public function scopeAttendanceDate($query, $date)
    {
        return $query->where('attendance_date', $date);
    }

    protected function getCalExceprion()
    {
        return CalendarException::where('date_exception', date('Y-m-d', strtotime($this->attended_at)))->first();
    }

    protected function getLeaveLog()
    {
        return LeaveLog::where('date', date('Y-m-d', strtotime($this->attended_at)))->first();
    }

    protected function checkEmployeeAreWorking()
    {
        if (date('Y-m-d', strtotime($this->attended_at)) == date('Y-m-d')) {
            return "Masuk";
        }
        return "Absen";
    }

    protected function ensureIsHoliday()
    {
        return ($this->in_at == null && $this->out_at == null) && !is_null($this->getCalExceprion());
    }

    protected function ensureIsNotPressent()
    {
        return ($this->in_at == null || $this->out_at == null) && is_null($this->getCalExceprion()) && is_null($this->getLeaveLog());
    }

    protected function ensureIsLeave()
    {
        return ($this->in_at == null || $this->out_at) && !is_null($this->getLeaveLog());
    }
}