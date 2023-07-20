<?php

namespace App\Http\Controllers\Admin\Home;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Room;
use App\Models\Config;
use App\Models\Department;
use App\Models\DocumentManagement;
use App\Models\Employee;
use App\Models\EmployeeContract;
use App\Models\Leave;
use App\Models\RoomFasility;
use App\Models\SalaryReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        view()->share('menu_active', route('admin.home.home.index'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
     $name = $request->get('name');
     $category = $request->get('category_id');
        if (!empty($name) && !empty($category)) {
            $product = Room::with('category')->where([['name', 'like', $name], ['category_id', '=',$category]])->paginate(8);
            return view('admin.home.index', compact('product'));
        }else if(!empty($name) && empty($category)){
            $product = Room::with('category')->where('name','!=', $name)->paginate(8);
            return view('admin.home.index', compact('product'));

        }else if(!empty($category) && empty($name)){
            $product = Room::with('category')->where('category_id', $category)->paginate(8);
            return view('admin.home.index', compact('product'));
        }
        
        $product = Room::with('category')->paginate(8);
        return view('admin.home.index', compact('product'));
    }

    public function yesterdayAttendanceByDept()
    {
        $departments = $this->department();

        $result = [];
        $notAttend = [];
        foreach ($departments as $value) {
            $value->attend = 0;
            $employee = Employee::leftJoin('departments', 'departments.id', '=', 'employees.department_id')->whereRaw("departments.path like '%$value->name%'")->count();
            $attend = Attendance::leftJoin('employees', 'employees.id', '=', 'attendances.employee_id')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->where('attendances.attendance_date', date('Y-m-d', strtotime('-1 day')))->whereRaw("departments.path like '%$value->name%'")->where('attendances.status', 1)->count();
            $notattend = Attendance::leftJoin('employees', 'employees.id', '=', 'attendances.employee_id')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->where('attendances.attendance_date', date('Y-m-d', strtotime('-1 day')))->whereRaw("departments.path like '%$value->name%'")->where('attendances.status', -1)->count();
            $value->attend = $attend;
            $value->notAttend = $notattend * -1;
            array_push($result, $value);
        }

        return $result;
    }

    public function grossSalaryInYear()
    {
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $salary = SalaryReport::whereMonth('period', $i)->whereYear('period', date('Y'))->sum('gross_salary');
            array_push($result, $salary);
        }
        return $result;
    }

    public function estimateSalary()
    {
        $salary = SalaryReport::whereMonth('period', date('m'))->whereYear('period', date('Y'))->where('salary_type', 'Monthly')->sum('net_salary');

        return $salary;
    }

    public function estimateSalaryHourly()
    {
        $salary = SalaryReport::whereMonth('period', date('m'))->whereYear('period', date('Y'))->where('salary_type', 'Hourly')->sum('net_salary');

        return $salary;
    }

    public function donutChart()
    {
        $employees = Employee::all()->count();
        $attendance = Attendance::where('attendance_date', date('Y-m-d', strtotime('-1 day')))->where('status', 1)->count();
        $notattendance = Attendance::where('attendance_date', date('Y-m-d', strtotime('-1 day')))->where('status', -1)->count();
        $leave = Leave::leftJoin('leave_settings', 'leave_settings.id', '=', 'leaves.leave_setting_id')->leftJoin('leave_logs', 'leave_logs.leave_id', '=', 'leaves.id')->whereRaw("upper(leave_settings.leave_name) not like 'ALPHA'")->where('leave_logs.date', date('Y-m-d', strtotime('-1 day')))->where('leaves.status', 1)->count();
        $alpha = Leave::leftJoin('leave_settings', 'leave_settings.id', '=', 'leaves.leave_setting_id')->leftJoin('leave_logs', 'leave_logs.leave_id', '=', 'leaves.id')->where('leaves.status', 1)->whereRaw("upper(leave_settings.leave_name) like 'ALPHA'")->where('leave_logs.date', date('Y-m-d', strtotime('-1 day')))->count();
        $off = $notattendance - ($leave + $alpha) > 0 ? $notattendance - ($leave + $alpha) : 0;
        $label = ['Attendances (' . $attendance . ')', 'Alpha (' . $alpha . ')', 'Leave (' . $leave . ')', 'Off (' . $off . ')'];
        $data = [$attendance, $alpha, $leave, $off];
        $result = array('label' => $label, 'data' => $data);
        return $result;
    }

    public function department()
    {
        $department = Department::where('parent_id', 0)->get();

        return $department;
    }

    public function yesterdayOvertimeReport()
    {
        $department = $this->department();
        $result = [];
        foreach ($department as $value) {
            $value->person = 0;
            $value->total = 0;
            $value->average = 0;

            $overtime = Attendance::leftJoin('employees', 'employees.id', '=', 'attendances.employee_id');
            $overtime->leftJoin('workingtime_details', function ($join) {
                $join->on('workingtime_details.workingtime_id', '=', 'attendances.workingtime_id');
                $join->on('workingtime_details.day', '=', 'attendances.day');
            });
            $overtime->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
            $overtime->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
            $overtime->leftJoin('workgroup_masters', 'workgroup_masters.id', '=', 'work_groups.workgroupmaster_id');
            $overtime->whereRaw("departments.path like '%$value->name%'");
            $overtime->where('attendances.attendance_date', date('Y-m-d', strtotime('-1 day')));
            $overtime->where('attendances.status', 1);
            $overtime->whereRaw("(attendances.adj_over_time > 0 or (((attendances.adj_working_time + attendances.adj_over_time) - workingtime_details.min_workhour) > 0 and (workgroup_masters.name = 'PKWT 2' or workgroup_masters.name = 'Outsourcing')))");
            $value->total = $overtime->sum(DB::raw('(attendances.adj_over_time + attendances.adj_working_time) - workingtime_details.min_workhour'));
            $value->person = $overtime->count();
            $value->average = $value->person > 0 ? $value->total / $value->person : 0;
            array_push($result, $value);
        }

        return $result;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::with(['category', 'fasilities'])->findOrFail($id);
        // $fasilities = RoomFasility::with(['fasilties'])->where('room_id', $id)->get();

        return view('admin.home.detail', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
