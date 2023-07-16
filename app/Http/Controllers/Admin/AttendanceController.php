<?php

namespace App\Http\Controllers\Admin;

ini_set('max_execution_time', 3600);

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Calendar;
use App\Models\Employee;
use App\Models\Workingtime;
use App\Models\OvertimeSchemeList;
use App\Models\WorkingtimeDetail;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use PHPExcel_Cell;

class AttendanceController extends Controller
{
    public function __construct()
    {
        View::share('menu_active', url('admin/' . 'attendance'));
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;

        //Count Data
        $query = DB::table('attendance_logs');
        $query->select('attendance_logs.*');
        $query->leftJoin('attendances', 'attendances.id', '=', 'attendance_logs.attendance_id');
        $query->leftJoin('employees', 'employees.id', '=', 'attendance_logs.employee_id');
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('attendance_logs');
        $query->select('attendance_logs.*');
        $query->leftJoin('attendances', 'attendances.id', '=', 'attendance_logs.attendance_id');
        $query->leftJoin('employees', 'employees.id', '=', 'attendance_logs.employee_id');
        $query->offset($start);
        $query->limit($length);
        $logs = $query->get();

        $data = [];
        foreach ($logs as $log) {
            $log->no = ++$start;
            $data[] = $log;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $employee = strtoupper(str_replace("'","''",$request->employee));
        $nik = $request->nik;
        $working_group = $request->working_group;
        $status = $request->status;
        $from = $request->from ? Carbon::parse($request->from)->startOfDay()->toDateTimeString() : null;
        $to = $request->to ? Carbon::parse($request->to)->endOfDay()->toDateTimeString() : null;

        //Count Data
        $query = DB::table('attendance_logs');
        $query->select('attendance_logs.*', 'employees.name as name', 'employees.nid as nid', 'workingtimes.working_time_type as working_group', 'workingtimes.description as description');
        $query->leftJoin('attendances', 'attendances.id', '=', 'attendance_logs.attendance_id');
        $query->leftJoin('employees', 'employees.id', '=', 'attendance_logs.employee_id');
        $query->leftJoin('workingtimes', 'workingtimes.id', '=', 'attendances.workingtime_id');
        if ($working_group) {
            // $query->where(function (Builder $q) use ($working_group) {
            //     foreach ($working_group as $key => $value) {
            //         if ($key == 0) {
            //             $q->Where('workingtimes.working_time_type', 'like', "%$value%");
            //         }
            //         $q->orWhere('workingtimes.working_time_type', 'like', "%$value%");
            //     }
            // });
            $query->whereIn('workingtimes.working_time_type', $working_group);
        }
        if ($employee) {
            $query->whereRaw("upper(employees.name) like '%$employee%'");
        }
        if ($nik) {
            $query->where("employees.nid", 'like', "%$nik%");
        }
        if ($status) {
            $query->whereIn('attendance_logs.type', $status);
        }
        if ($from && $to) {
            $query->whereBetween('attendance_logs.attendance_date', [$from, $to]);
        }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('attendance_logs');
        $query->select('attendance_logs.*', 'employees.name as name', 'employees.nid as nid', 'workingtimes.working_time_type as working_group', 'workingtimes.description as description');
        $query->leftJoin('attendances', 'attendances.id', '=', 'attendance_logs.attendance_id');
        $query->leftJoin('employees', 'employees.id', '=', 'attendance_logs.employee_id');
        $query->leftJoin('workingtimes', 'workingtimes.id', '=', 'attendances.workingtime_id');
        if ($working_group) {
            // $query->where(function (Builder $q) use ($working_group) {
            //     foreach ($working_group as $key => $value) {
            //         if ($key == 0) {
            //             $q->Where('workingtimes.working_time_type', 'like', "%$value%");
            //         }
            //         $q->orWhere('workingtimes.working_time_type', 'like', "%$value%");
            //     }
            // });
            $query->whereIn('workingtimes.working_time_type', $working_group);
        }
        if ($employee) {
            $query->whereRaw("upper(employees.name) like '%$employee%'");
        }
        if ($nik) {
            $query->where("employees.nid", 'like', "%$nik%");
        }
        if ($status) {
            $query->whereIn('attendance_logs.type', $status);
        }
        if ($from && $to) {
            $query->whereBetween('attendance_logs.attendance_date', [$from, $to]);
        }
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $attendances = $query->get();

        $data = [];
        foreach ($attendances as $attendance) {
            $attendance->no = ++$start;
            $data[] = $attendance;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query = DB::table('employees');
        $query->select('employees.name','employees.nid', 'employees.status');
        $query->where('employees.status', 1);
        $employees = $query->get();
        return view('admin.attendance.index', compact('employees'));
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

    public function import()
    {
        return view('admin.attendance.import');
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

    /**
     * Preview file excel to import page
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function preview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file'      => 'required|mimes:xlsx'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }
        $file = $request->file('file');
        try {
            $filetype   = \PHPExcel_IOFactory::identify($file);
            $objReader  = \PHPExcel_IOFactory::createReader($filetype);
            $objPHPExcel= $objReader->load($file);
        } catch (\Exception $e) {
            die("Error loading file " . pathinfo($file, PATHINFO_BASENAME) . ": " . $e->getMessage());
        }

        $data   = [];
        $no     = 1;
        $sheet  = $objPHPExcel->getActiveSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = PHPExcel_Cell::columnIndexFromString($sheet->getHighestColumn());
        $period = $sheet->getCellByColumnAndRow(2, 3)->getValue();
        for ($row = 5; $row <= $highestRow ; $row++) { 
            $nid = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $checkEmployee = Employee::whereRaw("upper(nid) = '$nid'")->first();
            if ($checkEmployee) {
                for ($col = 0; $col < $highestColumn; $col++) { 
                    $nextRow        = $row + 1;
                    $dataRow        = $sheet->getCellByColumnAndRow($col, $nextRow)->getValue();
                    $attendances    = $dataRow ? $this->getHourFromExplode($dataRow) : null;
                    $date           = $col + 1;
                    $attendanceDate = Carbon::createFromDate($request->year, $request->month, $date);
                    
                    // $attendance     = $this->getAttendanceInOut($attendances, $attendanceDate->toDateString());
                    $exceptionDate  = $this->employee_calendar($checkEmployee->id);
                    $day            = (in_array($attendanceDate->toDateString(), $exceptionDate)) ? 'Off' : changeDateFormat('D', $attendanceDate);
                    $shifts         = $this->get_workingtime($day);
                    $attendanceIn   = null;
                    $attendanceOut  = null;
                    $workingshift   = null;
                    if ($attendances) {
                        foreach ($attendances as $key => $value) {
                            foreach ($shifts as $key => $shift) {
                                $diff           = Carbon::parse($value)->diffInMinutes(Carbon::parse($shift->start));
                                if ($diff < 90) {
                                    $workingshift = $shift->workingtime_id;
                                    $attendanceIn = Carbon::parse($attendanceDate->toDateString() . ' ' . $value)->toDateTimeString();
                                } else {
                                    $diffOut    = Carbon::parse($value)->diffInMinutes(Carbon::parse($shift->finish));
                                    if ($diffOut < 90) {
                                        $workingshift = $workingshift ? $workingshift : $shift->workingtime_id;
                                        $attendanceOut = Carbon::parse($attendanceDate->toDateString() . ' ' . $value)->toDateTimeString();
                                    }
                                }
                            }
                        }
                    }
                    $workingshiftId = $workingshift ? Workingtime::find($workingshift) : null;
                    
                    $data[] = array(
                        'index'             => $no,
                        'employee_id'       => $checkEmployee->id,
                        'employee_name'     => $checkEmployee->name,
                        'employee_nid'      => $checkEmployee->nid,
                        'deparment_name'    => $checkEmployee->department ? $checkEmployee->department->name : null,
                        'attendance_date'   => $attendanceDate->toDateString(),
                        'attendance_in'     => $attendanceIn,
                        'attendance_out'    => $attendanceOut,
                        'workingtime_id'    => $workingshiftId ? $workingshiftId->id : null,
                        'workingtime_name'  => $workingshiftId ? $workingshiftId->description : null,
                        'day'               => $day,
                        'shifts'               => $shifts,
                    );
                    $no++;
                }
            }
        }
        return response()->json([
            'status'    => true,
            'data'      => $data
        ], 200);
    }

    /**
     * Explode string from row excel to get hour where is using PHP_EOL
     *
     * @param string $data
     * @return array
     */
    public function getHourFromExplode(string $data)
    {
        $explode        = explode(PHP_EOL, $data);
        $attendances    = array_filter($explode);
        
        return $attendances;
    }

    public function preview2(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file'         => 'required|mimes:xlsx'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }
        $file = $request->file('file');
        try {
            $filetype       = \PHPExcel_IOFactory::identify($file);
            $objReader      = \PHPExcel_IOFactory::createReader($filetype);
            $objPHPExcel    = $objReader->load($file);
        } catch (\Exception $e) {
            die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
        }
        $data     = [];
        $no = 1;
        $sheet = $objPHPExcel->getActiveSheet(0);
        $highestRow = $sheet->getHighestRow();
        for ($row = 3; $row <= $highestRow; $row++) {
            $personel_id = $sheet->getCellByColumnAndRow(0, $row)->getValue();
            $first_name = $sheet->getCellByColumnAndRow(1, $row)->getValue();
            $last_name = $sheet->getCellByColumnAndRow(2, $row)->getValue();
            $department_name = $sheet->getCellByColumnAndRow(3, $row)->getValue();
            $attendance_area = $sheet->getCellByColumnAndRow(4, $row)->getValue();
            $serial_number = $sheet->getCellByColumnAndRow(5, $row)->getValue();
            $device_name = $sheet->getCellByColumnAndRow(6, $row)->getValue();
            $point_name = $sheet->getCellByColumnAndRow(7, $row)->getValue();
            $attendance_date = $sheet->getCellByColumnAndRow(8, $row)->getValue();
            $date_source = $sheet->getCellByColumnAndRow(9, $row)->getValue();
            if ($personel_id) {
                $employee = Employee::whereRaw("upper(nid) like '%$personel_id%'")
                    ->get()
                    ->first();
                $data[] = array(
                    'index' => $no,
                    'employee_id' => $employee ? $employee->id : null,
                    'personel_id' => $personel_id,
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'department_name' => $department_name,
                    'attendance_area' => $attendance_area,
                    'serial_number' => $serial_number,
                    'device_name' => $device_name,
                    'point_name' => $point_name,
                    'attendance_date' => $attendance_date,
                    'date_source' => $date_source
                );
                $no++;
            }
        }
        return response()->json([
            'status'     => true,
            'data'     => $data
        ], 200);
    }

    public function employee_calendar($id)
    {
        $query_calendar = DB::table('employees');
        $query_calendar->select('calendar_exceptions.*');
        $query_calendar->leftJoin('calendars', 'calendars.id', '=', 'employees.calendar_id');
        $query_calendar->leftJoin('calendar_exceptions', 'calendar_exceptions.calendar_id', '=', 'calendars.id');
        $query_calendar->where('employees.id', '=', $id);
        $calendar = $query_calendar->get();
        $exception_date = [];
        foreach ($calendar as $date) {
            $exception_date[] = $date->date_exception;
        }

        return $exception_date;
    }

    public function employee_worktime($id)
    {
        $query = DB::table('attendances');
        $query->select('attendances.*', 'workingtime_details.workingtime_id as workingtime_id', 'workingtime_details.start as start', 'workingtime_details.finish as finish', 'workingtime_details.min_in as min_in', 'workingtime_details.max_out as max_out', 'workingtime_details.workhour as workhour', 'workingtime_details.day as day', 'employees.working_time as working_time');
        $query->leftJoin('workingtimes', 'workingtimes.id', '=', 'attendances.workingtime_id');
        $query->leftJoin('workingtime_details', 'workingtime_details.workingtime_id', '=', 'workingtimes.id');
        $query->leftJoin('employees', 'employees.id', '=', 'attendances.employee_id');
        $query->where('employees.id', '=', $id);
        $worktime = $query->first();

        return $worktime;
    }

    public function get_workingtime($day)
    {
        $query  = Workingtime::select(
            'workingtimes.description as shift',
            'workingtime_details.workingtime_id as workingtime_id',
            'workingtime_details.start as start',
            'workingtime_details.finish as finish',
            'workingtime_details.min_in as min_in',
            'workingtime_details.max_out as max_out',
            'workingtime_details.workhour as workhour',
            'workingtime_details.min_workhour as min_workhour',
            'workingtime_details.day as day'
        );
        $query->leftJoin('workingtime_details', 'workingtime_details.workingtime_id', '=', 'workingtimes.id');
        $query->where('workingtimes.working_time_type', '!=', 'Non-Shift');
        $query->where('workingtime_details.status', 1);
        $query->where('workingtime_details.day', $day);

        return $query->get();
    }

    public function get_breaktime($workgroup)
    {
        $query = DB::table('break_times');
        $query->select('break_times.*');
        $query->leftJoin('break_time_lines', 'break_time_lines.breaktime_id', '=', 'break_times.id');
        $query->where('break_time_lines.workgroup_id', '=', $workgroup);

        return $query->get();
    }

    public function get_shift($day)
    {
        $query = DB::table('workingtimes');
        $query->select('workingtimes.*', 'workingtime_details.workingtime_id as workingtime_id', 'workingtime_details.start as start', 'workingtime_details.finish as finish', 'workingtime_details.min_in as min_in', 'workingtime_details.max_out as max_out', 'workingtime_details.workhour as workhour', 'workingtime_details.day as day');
        $query->leftJoin('workingtime_details', 'workingtime_details.workingtime_id', '=', 'workingtimes.id');
        $query->where('workingtimes.working_time_type', '=', 'Non-Shift');
        $query->where('workingtime_details.status', '=', 1);
        $query->where('workingtime_details.day', '=', $day);

        return $query->first();
    }

    public function checkWorkingtime($id, $day)
    {
        $query = WorkingtimeDetail::whereNotNull('min_workhour')->where('workingtime_id', $id)->where('day', $day);

        return $query->first();
    }

    public function storemass(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'attendance'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }
        $attendances = json_decode($request->attendance);
        DB::beginTransaction();
        foreach ($attendances as $key => $attendance) {
            if ($attendance->workingtime_id) {
                $checkAlreadyApprove    = Attendance::where('employee_id', $attendance->employee_id)->where('attendance_date', $attendance->attendance_date)->first();
                $wt_check = roundedTime(countWorkingTime($attendance->attendance_in, $attendance->attendance_out));
                $ot = 0;
                $shift = WorkingtimeDetail::where('workingtime_id', $attendance->workingtime_id)->where('day', $attendance->day)->first();
                if (!$shift) {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Shift for this employee name $attendance->employee_name at this day $attendance->day and date $attendance->attendance_date and this workingtime $attendance->workingtime_name"
                    ], 400);
                }
                if ($wt_check - $shift->workhour >= 0) {
                    $ot = $wt_check - $shift->workhour;
                    $wt = $wt_check - $ot;
                }
                if ($attendance->day == 'Off') {
                    $ot = $wt_check;
                    $wt = 0;
                }
                $overtimeSchema = OvertimeSchemeList::where('recurrence_day', $attendance->day)->first();
                if ($checkAlreadyApprove) {
                    if ($checkAlreadyApprove->status == 0) {
                        $checkAlreadyApprove->attendance_in     = $attendance->attendance_in;
                        $checkAlreadyApprove->attendance_out    = $attendance->attendance_out;
                        $checkAlreadyApprove->adj_working_time  = $wt;
                        $checkAlreadyApprove->adj_over_time     = $ot;
                        $checkAlreadyApprove->overtime_scheme_id= $overtimeSchema ? $overtimeSchema->overtime_scheme_id : null;
                        $checkAlreadyApprove->workingtime_id    = $attendance->workingtime_id;
                        $checkAlreadyApprove->save();
    
                        if (!$checkAlreadyApprove) {
                            DB::rollBack();
                            return response()->json([
                                'status'    => false,
                                'message'   => $checkAlreadyApprove
                            ], 400);
                        }
                    }
                } else {
                    $createAttendance   = Attendance::create([
                        'employee_id'       => $attendance->employee_id,
                        'attendance_date'   => $attendance->attendance_date,
                        'attendance_in'     => $attendance->attendance_in,
                        'attendance_out'    => $attendance->attendance_out,
                        'status'            => 0,
                        'adj_working_time'  => $wt,
                        'adj_over_time'     => $ot,
                        'workingtime_id'    => $attendance->workingtime_id,
                        'day'               => $attendance->day,
                        'overtime_scheme_id'=> $overtimeSchema ? $overtimeSchema->overtime_scheme_id : null,
                    ]);
                    if (!$createAttendance) {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => $createAttendance
                        ], 400);
                    }
    
                    if ($createAttendance->attendance_in) {
                        $createAttendanceLog    = AttendanceLog::create([
                            'attendance_id'     => $createAttendance->id,
                            'employee_id'       => $createAttendance->employee_id,
                            'type'              => 1,
                            'attendance_date'   => $createAttendance->attendance_in,
                        ]);
                        if (!$createAttendanceLog) {
                            DB::rollBack();
                            return response()->json([
                                'status'    => false,
                                'message'   => $createAttendanceLog
                            ], 400);
                        }
                    }
                    if ($createAttendance->attendance_out) {
                        $createAttendanceLogOut = AttendanceLog::create([
                            'attendance_id'     => $createAttendance->id,
                            'employee_id'       => $createAttendance->employee_id,
                            'type'              => 0,
                            'attendance_date'   => $createAttendance->attendance_out,
                        ]);
                        if (!$createAttendanceLogOut) {
                            DB::rollBack();
                            return response()->json([
                                'status'    => false,
                                'message'   => $createAttendanceLogOut
                            ], 400);
                        }
                    }
                }
            }
            else{
                $checkAlreadyApprove    = Attendance::where('employee_id', $attendance->employee_id)->where('attendance_date', $attendance->attendance_date)->first();
                $overtimeSchema = OvertimeSchemeList::where('recurrence_day', $attendance->day)->first();
                if ($checkAlreadyApprove) {
                    if ($checkAlreadyApprove->status == 0) {
                        $checkAlreadyApprove->overtime_scheme_id= $overtimeSchema ? $overtimeSchema->overtime_scheme_id : null;
                        $checkAlreadyApprove->save();
    
                        if (!$checkAlreadyApprove) {
                            DB::rollBack();
                            return response()->json([
                                'status'    => false,
                                'message'   => $checkAlreadyApprove
                            ], 400);
                        }
                    }
                }
                else{
                    $createAttendance   = Attendance::create([
                        'employee_id'       => $attendance->employee_id,
                        'attendance_date'   => $attendance->attendance_date,
                        'attendance_in'     => null,
                        'attendance_out'    => null,
                        'status'            => 0,
                        'adj_working_time'  => 0,
                        'adj_over_time'     => 0,
                        'workingtime_id'    => null,
                        'day'               => $attendance->day,
                        'overtime_scheme_id'=> $overtimeSchema ? $overtimeSchema->overtime_scheme_id : null,
                    ]);
                    if (!$createAttendance) {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => $createAttendance
                        ], 400);
                    }
                }
            }
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('attendance.index'),
        ], 200);
    }
}