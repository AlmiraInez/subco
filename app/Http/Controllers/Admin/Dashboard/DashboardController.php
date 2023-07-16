<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Transaction;
use App\Models\Department;
use App\Models\Room;
use App\Models\Employee;
use App\Models\Payment;
use App\Models\Leave;
use App\Models\Invoice;
use App\Models\SalaryReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        view()->share('menu_active', route('admin.dashboard.index'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // @$config = Config::where('option', 'expired_contract')->get()->first();
        // @$config_document = Config::where('option', 'expired_document')->get()->first();
        // @$todata = date('Y-m-d', strtotime('+' . $config->value . " Days"));
        // @$todata_document = date('Y-m-d', strtotime('+' . $config_document->value . " Days"));
        // // dd($todata);

        // // $attendances = Attendance::with(['employee' => function($query){
        // //     $query->where('employees.status', 1);
        // // }])->where('attendances.status', 0)->count('id');
        // $query = DB::table('attendances');
        // $query->select('attendances.*','employees.name as name', 'employees.nid as nid', 'workingtimes.working_time_type as working_type', 'workingtimes.description as description', 'departments.name as department_name', 'titles.name as title_name', 'work_groups.name as workgroup_name', 'overtime_schemes.scheme_name as scheme_name');
        // $query->leftJoin('employees', 'employees.id', '=', 'attendances.employee_id');
        // $query->leftJoin('workingtimes', 'workingtimes.id', '=', 'attendances.workingtime_id');
        // $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
        // $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
        // $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
        // $query->where('attendances.status', 0);
        // $query->where('employees.status', 1);
        // $data['attendances'] = $query->count('attendances.id');
        // $data['leaves']      = Leave::where('status', 0)->count('id');

        // $data['contracts'] = EmployeeContract::select('employee_id')->distinct()->where('end_date', '<=', $todata)->get()->count();
        // // $contracts = EmployeeContract::with(['employee' => function($query){ $query->where('employees.status', 1);}])->distinct()->where('end_date', '<=', $todata)->get()->count();

        // $now = Carbon::now()->format('Y-m-d');
        // $data['documents']   = DocumentManagement::whereRaw("DATE_PART('day',expired_date::timestamp - '$now'::timestamp) <= nilai")->where('expired_date', '>=', $now)->count('id');
        // $data['yesterdayAttendance'] = Attendance::where('attendance_date', date('Y-m-d', strtotime('-1 day')))->where('status', 1)->count('id');
        // $yesterdayNotAttend = Attendance::where('attendance_date', date('Y-m-d', strtotime('-1 day')))->where('status', -1)->count('id');
        // $employeeTotal = Employee::all()->count('id');
        // $dayBeforeAttend = Attendance::where('attendance_date', date('Y-m-d', strtotime('-2 day')))->where('status', 1)->count('id');
        // $dayBeforeNotAttend = Attendance::where('attendance_date', date('Y-m-d', strtotime('-2 day')))->where('status', -1)->count('id');
        // if (($data['yesterdayAttendance'] + $dayBeforeAttend) > 0) {
        //     $data['dayBeforeCount'] = round((($data['yesterdayAttendance'] - $dayBeforeAttend) / ($data['yesterdayAttendance'] + $dayBeforeAttend)) * 100);
        // } else {
        //     $data['dayBeforeCount'] = 0;
        // }
        // if (($yesterdayNotAttend + $dayBeforeNotAttend) > 0) {
        //     $data['dayBeforeNotCount'] = round((($yesterdayNotAttend - $dayBeforeNotAttend) / ($yesterdayNotAttend + $dayBeforeNotAttend)) * 100);
        // } else {
        //     $data['dayBeforeNotCount'] = 0;
        // }
        // $data['yesterdayOvertime'] = $this->yesterdayOvertimeReport();
        // $data['yesterdayAttendancebyDept'] = $this->yesterdayAttendanceByDept();
        // $data['estimateSalary'] = $this->estimateSalary();
        // $data['estimateSalaryHourly'] = $this->estimateSalaryHourly();
        // $data['grossSalaryYear'] = $this->grossSalaryInYear();
        // $data['donutChart'] = $this->donutChart();
        $roomavailable = Room::where('status', 1)->count('id');
        $checkin = Transaction::where('status', Transaction::STAT_CHECKIN)->count('id');
        $booking = Transaction::where('status', Transaction::STAT_BOOKING)->count('id');
        $transapproval = Transaction::where('stat_approval', Transaction::STAT_PROCCESSING)->count('id');
        $payapproval = Payment::where('stat_approval', Payment::STAT_PROCCESSING)->count('id');
        $invapproval = Invoice::where('payment_status', Invoice::STAT_UNPAID)->count('id');

        return view('admin.dashboard.index', compact('roomavailable', 'checkin', 'booking', 'transapproval','payapproval', 'invapproval'));
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
        //
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
