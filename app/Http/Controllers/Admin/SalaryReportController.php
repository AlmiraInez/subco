<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Allowance;
use App\Models\AllowanceRule;
use App\Models\AlphaPenalty;
use App\Models\DeliveryOrder;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Title;
use App\Models\Overtime;
use App\Models\Attendance;
use App\Models\WorkGroup;
use App\Models\PphReport;
use App\Models\PphReportDetail;
use App\Models\EmployeeAllowance;
use App\Models\EmployeeSalary;
use App\Models\Leave;
use App\Models\SalaryReport;
use App\Models\SalaryReportDetail;
use App\Models\EnviostoreServer\Expense;
use App\Models\EnviostoreServer\ExpenseDetail;
use App\Models\EnviostoreServer\AccountMapping;
// use App\Models\Title;
// use App\Models\WorkGroup;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Dompdf\Dompdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Validation\Rule;
use PDF;
use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_Cell_DataType;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Font;

class SalaryReportController extends Controller
{
  function __construct()
  {
    View::share('menu_active', url('admin/' . 'salaryreport'));
  }

  public function read(Request $request)
  {
    $start = $request->start;
    $length = $request->length;
    $search = strtoupper($request->search['value']);
    $sort = $request->columns[$request->order[0]['column']]['data'];
    $dir = $request->order[0]['dir'];
    $employee_id = $request->employee_id;
    $department_ids = $request->department_id ? $request->department_id : null;
    $position = $request->position ? $request->position : null;
    $workgroup_id = $request->workgroup_id ? $request->workgroup_id : null;
    $month = $request->month;
    // dd($month);
    $year = $request->year;
    $nid = $request->nid;
    $status = $request->status;
    $type = $request->type;

    //Count Data
    $query = DB::table('salary_reports');
    $query->select('salary_reports.*', 'employees.name as employee_name', 'employees.nid as nik', 'titles.name as title_name', 'departments.name as department_name', 'work_groups.name as workgroup_name', 'employees.department_id as department_id', 'employees.title_id as title_id', 'employees.workgroup_id as workgroup_id');
    $query->leftJoin('employees', 'employees.id', '=', 'salary_reports.employee_id');
    $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
    $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
    $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
    if ($employee_id) {
      $query->whereIn('salary_reports.employee_id', $employee_id);
    }
    if ($nid) {
      $query->whereRaw("employees.nid like '%$nid%'");
    }
    if ($month) {
      // $query->whereMonth('salary_reports.period', '=', $month);
      $query->where(function($query1) use ($month){
        foreach ($month as $q_month) {
          $query1->orWhereRaw("EXTRACT(MONTH FROM period) = $q_month");
        }
      });
    }
    if ($year) {
      // $query->whereYear('salary_reports.period', '=', $year);
      $query->where(function($query2) use ($year){
        foreach ($year as $q_year) {
          $query2->orWhereRaw("EXTRACT(YEAR FROM period) = $q_year");
        }
      });
    }
    if ($department_ids) {
      $string = '';
      foreach ($department_ids as $dept) {
        $string .= "departments.path like '%$dept%'";
        if (end($department_ids) != $dept) {
          $string .= ' or ';
        }
      }
      $query->whereRaw('(' . $string . ')');
    }
    if ($position != "") {
      $query->whereIn('employees.title_id', $position);
    }
    if ($workgroup_id != "") {
      $query->whereIn('employees.workgroup_id', $workgroup_id);
    }
    if ($status) {
      $query->whereIn('salary_reports.status', $status);
    }
    if ($type) {
      $query->whereIn('salary_reports.salary_type', $type);
    }
    $recordsTotal = $query->count();

    //Select Pagination
    $query = DB::table('salary_reports');
    $query->select('salary_reports.*', 'employees.name as employee_name', 'employees.nid as nik', 'titles.name as title_name', 'departments.name as department_name', 'work_groups.name as workgroup_name', 'employees.department_id as department_id', 'employees.title_id as title_id', 'employees.workgroup_id as workgroup_id');
    $query->leftJoin('employees', 'employees.id', '=', 'salary_reports.employee_id');
    $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
    $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
    $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
    if ($employee_id) {
      $query->whereIn('salary_reports.employee_id', $employee_id);
    }
    if ($nid) {
      $query->whereRaw("employees.nid like '%$nid%'");
    }
    if ($month) {
      // $query->whereMonth('salary_reports.period', '=', $month);
      $query->where(function($query1) use ($month){
        foreach ($month as $q_month) {
          $query1->orWhereRaw("EXTRACT(MONTH FROM period) = $q_month");
        }
      });
    }
    if ($year) {
      // $query->whereYear('salary_reports.period', '=', $year);
      $query->where(function($query2) use ($year){
        foreach ($year as $q_year) {
          $query2->orWhereRaw("EXTRACT(YEAR FROM period) = $q_year");
        }
      });
    }
    if ($department_ids) {
      $string = '';
      foreach ($department_ids as $dept) {
        $string .= "departments.path like '%$dept%'";
        if (end($department_ids) != $dept) {
          $string .= ' or ';
        }
      }
      $query->whereRaw('(' . $string . ')');
    }
    if ($position != "") {
      $query->whereIn('employees.title_id', $position);
    }
    if ($workgroup_id != "") {
      $query->whereIn('employees.workgroup_id', $workgroup_id);
    }
    if ($status) {
      $query->whereIn('salary_reports.status', $status);
    }
    if ($type) {
      $query->whereIn('salary_reports.salary_type', $type);
    }
    $query->offset($start);
    $query->limit($length);
    $query->orderBy('period', $dir);
    $query->orderBy($sort, $dir);
    $reports = $query->get();
    $data = [];
    foreach ($reports as $report) {
      $report->no = ++$start;
      $report->net_salary = number_format($report->net_salary, 0, ',', '.');
      $report->period = changeDateFormat('F - Y', $report->period);
      $data[] = $report;
    }
    return response()->json([
      'draw' => $request->draw,
      'recordsTotal' => $recordsTotal,
      'recordsFiltered' => $recordsTotal,
      'data' => $data
    ], 200);
  }
  public function readapproval(Request $request)
  {
    $start = $request->start;
    $length = $request->length;
    $search = strtoupper($request->search['value']);
    $sort = $request->columns[$request->order[0]['column']]['data'];
    $dir = $request->order[0]['dir'];
    $employee_id = $request->employee_id;
    $department_id = $request->department_id;
    $position = $request->position;
    $workgroup_id = $request->workgroup_id;
    $month = $request->month;
    $year = $request->year;
    $nid = $request->nid;
    $status = $request->status;
    $type = $request->type;

    //Count Data
    $query = DB::table('salary_reports');
    $query->select('salary_reports.*', 'employees.name as employee_name', 'employees.nid as nik', 'titles.name as title_name', 'departments.name as department_name', 'work_groups.name as workgroup_name', 'employees.department_id as department_id', 'employees.title_id as title_id', 'employees.workgroup_id as workgroup_id');
    $query->leftJoin('employees', 'employees.id', '=', 'salary_reports.employee_id');
    $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
    $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
    $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
    $query->where('salary_reports.status', 0);

    $recordsTotal = $query->count();

    //Select Pagination
    $query = DB::table('salary_reports');
    $query->select('salary_reports.*', 'employees.name as employee_name', 'employees.nid as nik', 'titles.name as title_name', 'departments.name as department_name', 'work_groups.name as workgroup_name', 'employees.department_id as department_id', 'employees.title_id as title_id', 'employees.workgroup_id as workgroup_id');
    $query->leftJoin('employees', 'employees.id', '=', 'salary_reports.employee_id');
    $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
    $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
    $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
    $query->where('salary_reports.status', 0);


    $query->offset($start);
    $query->limit($length);
    $query->orderBy('id', 'asc');
    $reports = $query->get();
    $data = [];
    foreach ($reports as $report) {
      $report->no = ++$start;
      $report->net_salary = number_format($report->net_salary, 0, ',', '.');
      $report->period = changeDateFormat('F - Y', $report->period);
      $data[] = $report;
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
    // $employees = Employee::all();
    $emp = DB::table('employees');
    $emp->select('employees.*');
    $emp->where('status', 1);
    $employees = $emp->get();
    // $departments = Department::all();
    $query = DB::table('departments');
    $query->select('departments.*');
    $query->orderBy('path','asc');
    $departments = $query->get();
    $workgroups = WorkGroup::all();
    $titles = Title::all();

    return view('admin.salaryreport.index', compact('employees', 'departments', 'workgroups', 'titles'));
  }
  public function indexapproval()
  {
    return view('admin.salaryreport.indexapproval');
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

  public function approve(Request $request)
  {
    if ($request->checksalary) {
      $approves = $request->checksalary;
      DB::beginTransaction();
      foreach ($approves as $id) {
        $salary = SalaryReport::with('employee')->find($id);
        $salary->status = 1;
        $salary->save();
        // echo $salary;
        // return;
        if($salary){
          $expense = Expense::create([
            'store_id'	 		    => 1,
            'expense_no'        => 'SR0001',
            'transaction_date'	=> date("Y-m-d"),
            'purchase_id'	 	    => $this->get_openmap('payment_method_import'),
            'paylater'			    => 0,
            'billing_address' 	=> "-",
            'subtotal' 			    => $salary->net_salary,
            'tax_total' 		    => 0,
            'amount'			      => $salary->net_salary,
            'memo' 				      => "Pembayaran Gaji ".$salary->employee->name,
            'status'			      => 0,
          ]);
          if($expense){
            $expensedetail = ExpenseDetail::create([
              'expense_id'      => $expense->id,
              'account_id'      => $this->get_openmap('salary_expense'),
              'description'     => 'Gaji '.$salary->employee->name.' '.date('F Y', strtotime($salary->period)),
              'tax_id'          => null,
              'total'           => $salary->net_salary,
              'tax_value'       => 0,
            ]);
            if(!$expensedetail){
              DB::rollBack();
              return response()->json([
                'status'      => false,
                'message'     => 'File Detail Biaya Tidak Dapat Dimasukkan'
              ], 400);
            }

          }
          else{
            DB::rollBack();
            return response()->json([
              'status'      => false,
              'message'     => 'File Biaya Tidak Dapat Dimasukkan'
            ], 400);
          }
        }else{
          DB::rollBack();
          return response()->json([
            'status'      => false,
            'message'     => $salary
          ], 400);
        }
      }
      DB::commit();
      return response()->json([
        'status'     => true,
        'message'     => 'Salary report was successfully approved',
      ], 200);
    }
  }
  function get_openmap($query){
    $data = [];
    $read = AccountMapping::where('code', $query)
                ->get();
    foreach ($read as $mapping) {
                $data = $mapping;
    }
    return $data->coa_id;

}

  public function waitingapproval(Request $request)
  {
    $salary = SalaryReport::find($request->id);
    DB::beginTransaction();
    $salary->status = 0;
    $salary->save();
    if (!$salary) {
      DB::rollBack();
      return response()->json([
        'status'      => false,
        'message'     => $salary
      ], 400);
    }
    DB::commit();
    return response()->json([
      'status'     => true,
      'results'   => route('salaryreport.index'),
    ], 200);
  }

  public function check_periode($month, $year, $employee)
  {
    $exists = SalaryReport::whereMonth('period', '=', $month)->whereYear('period', '=', $year)->where('employee_id', '=', $employee)->first();

    return $exists;
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

  public function get_employee_salary($id)
  {
    $basesalary = EmployeeSalary::where('employee_id', '=', $id)->orderBy('created_at', 'desc')->first();

    return $basesalary;
  }

  public function get_additional_allowance($id, $month, $year)
  {
    $query = DB::table('employee_allowances');
    $query->select('employee_allowances.*', 'allowances.allowance as description');
    $query->leftJoin('allowances', 'allowances.id', '=', 'employee_allowances.allowance_id');
    $query->leftJoin('allowance_categories', 'allowance_categories.key', '=', 'allowances.category');
    $query->where('employee_allowances.employee_id', '=', $id);
    $query->where('employee_allowances.month', '=', $month);
    $query->where('employee_allowances.year', '=', $year);
    $query->where('employee_allowances.status', '=', 1);
    $query->where('allowance_categories.type', '=', 'additional');
    $query->where('employee_allowances.type', '!=', 'automatic');
    $allowances = $query->get();

    $data = [];
    foreach ($allowances as $allowance) {
      $data[] = $allowance;
    }

    return $data;
  }

  public function get_deduction($id, $month, $year)
  {
    $query = DB::table('employee_allowances');
    $query->select('employee_allowances.*', 'allowances.allowance as description');
    $query->leftJoin('allowances', 'allowances.id', '=', 'employee_allowances.allowance_id');
    $query->leftJoin('allowance_categories', 'allowance_categories.key', '=', 'allowances.category');
    $query->where('employee_allowances.employee_id', '=', $id);
    $query->where('employee_allowances.month', '=', $month);
    $query->where('employee_allowances.year', '=', $year);
    $query->where('employee_allowances.status', '=', 1);
    $query->where('allowance_categories.type', '=', 'deduction');
    $query->where('employee_allowances.type', '!=', 'automatic');
    $allowances = $query->get();

    $data = [];
    foreach ($allowances as $allowance) {
      $data[] = $allowance;
    }

    return $data;
  }

  public function get_overtime($id, $month, $year)
  {
    $query = DB::table('overtimes');
    $query->select('overtimes.*');
    $query->where('overtimes.employee_id', '=', $id);
    $query->where('overtimes.final_salary', '>', 0);
    $query->whereMonth('date', '=', $month);
    $query->whereYear('date', '=', $year);
    $salaries = $query->sum('final_salary');

    return $salaries;
  }

  public function get_attendance($id, $month, $year)
  {
    $query = DB::table('attendances');
    $query->select('attendances.*');
    $query->where('attendances.employee_id', '=', $id);
    $query->where('attendances.status', '=', 1);
    $query->whereMonth('attendance_date', '=', $month);
    $query->whereYear('attendance_date', '=', $year);
    $query->where('day', '!=', 'Off');
    $attendances = $query->get();

    $data = [];
    foreach ($attendances as $attendance) {
      $data[] = $attendance;
    }

    return $data;
  }

    public function get_dayoff_attendance($id, $month, $year)
    {
      $query = DB::table('attendances');
      $query->select('attendances.*');
      $query->where('attendances.employee_id', '=', $id);
      $query->where('attendances.status', '!=', 0);
      $query->whereMonth('attendance_date', '=', $month);
      $query->whereYear('attendance_date', '=', $year);
      $query->whereNull('attendance_in');
      $query->whereNull('attendance_out');
      $attendances = $query->get();

      return $attendances->count();
    }

    public function get_maxoff($id, $month, $year)
    {
      $employee = Employee::find($id);
      $query = DB::table('calendar_maxoffs');
      $query->select('calendar_maxoffs.*');
      $query->where('month', '=', $month);
      $query->where('year', '=', $year);
      $query->where('calendar_id', '=', $employee->calendar_id);
      $calendar_maxoffs = $query->first();

      return $calendar_maxoffs->amount_day_off;
    }

  public function get_attendance_allowance($id, $month, $year)
  {
    // $employee_calendar = $this->employee_calendar($id);
    // $amonth = dateInAMonth($month, $year);
    // $work_date = [];
    // // To Create Workdate
    // foreach ($amonth as $key => $value) {
    //   if (!in_array($value, $employee_calendar)) {
    //     $work_date[] = $value;
    //   }
    // }

    // $attendance = $this->get_attendance($id, $month, $year);

    // $qty_absent = abs(count($work_date) - count($attendance));
    // $allowance = AllowanceRule::with('allowance')->where('qty_absent', '=', $qty_absent >= 2 ? 2 : $qty_absent)->first();
    // $attendance_allowance = 0;
    // $basesalary = $this->get_employee_salary($id);
    
    // if($allowance){
    //   if ($allowance->qty_allowance > 0) {
    //     $attendance_allowance = $allowance->qty_allowance * ($basesalary->amount / 30);
    //   }
    // }

    $employee_allowance = EmployeeAllowance::with('allowance')->where('status', 1)->where('month', $month)->where('year', $year)->where('employee_id', $id)->whereHas('allowance', function ($q) {
      $q->where('category', 'like', 'tunjanganKehadiran');
    })->first();
    // dd($employee_allowance);
    return $employee_allowance ? $employee_allowance->value : 0;
  }

  public function get_leave($id, $month, $year)
  {
    $query = DB::table('leaves');
    $query->select('leaves.*');
    $query->leftJoin('leave_logs', 'leave_logs.leave_id', '=', 'leaves.id');
    $query->where('leaves.employee_id', '=', $id);
    $query->where('leaves.status', '=', 1);
    $query->where('leave_logs.type', '=', 'fullday');
    $query->whereMonth('leave_logs.date', '=', $month);
    $query->whereYear('leave_logs.date', '=', $year);
    $leaves = $query->get();

    $data = [];
    foreach ($leaves as $leave) {
      $data[] = $leave;
    }

    return $data;
  }

  public function get_alpha($id, $month, $year)
  {
    $query = DB::table('leaves');
    $query->select('leaves.*');
    $query->leftJoin('leave_settings', 'leave_settings.id', '=', 'leaves.leave_setting_id');
    $query->leftJoin('leave_logs', 'leaves.id', '=', 'leave_logs.leave_id');
    $query->whereMonth('leave_logs.date', $month);
    $query->whereYear('leave_logs.date', $year);
    $query->where('leave_settings.description', 0);
    $query->where('leaves.employee_id', $id);
    $query->where('leaves.status', 1);
    $leaves = $query->get();

    return $leaves->count();
  }

  public function getAlphaData($id, $month, $year)
  {
    $query    = AlphaPenalty::where('employee_id', $id)->whereMonth('date', $month)->whereYear('date', $year);

    return $query->sum('penalty');
  }

  public function gross_salary($id)
  {
    $query = DB::table('salary_reports');
    $query->select('salary_reports.*');
    $query->leftJoin('salary_report_details', 'salary_report_details.salary_report_id', '=', 'salary_reports.id');
    $query->where('salary_report_details.salary_report_id', '=', $id);
    $query->where('salary_report_details.type', '=', 1);
    $gross = $query->sum('salary_report_details.total');

    return $gross;
  }

  public function deduction_salary($id)
  {
    $query = DB::table('salary_reports');
    $query->select('salary_reports.*');
    $query->leftJoin('salary_report_details', 'salary_report_details.salary_report_id', '=', 'salary_reports.id');
    $query->where('salary_report_details.salary_report_id', '=', $id);
    $query->where('salary_report_details.type', '=', 0);
    $deduction = $query->sum('salary_report_details.total');

    return $deduction;
  }

  public function get_pkp($id)
  {
    $query = DB::table('ptkps');
    $query->select('ptkps.*');
    $query->where('ptkps.key', '=', $id);
    $ptkp = $query->first();

    return $ptkp;
  }

  public function get_driver_allowance($id, $month, $year)
  {
    $query = DB::table('driver_allowance_lists');
    $query->select('driver_allowance_lists.*');
    $query->where('driver_id', $id);
    $query->whereMonth('date', $month);
    $query->whereYear('date', $year);

    return $query->sum('value');
  }

  public function getLatestId()
  {
    $read = SalaryReport::max('id');
    return $read + 1;
  }

  public function getAllDeliveryOrder($driver, $month, $year)
  {
    $deliveryOrder = DeliveryOrder::whereMonth('date', '=', $month)->whereYear('date', '=', $year)->where('driver_id', '=', $driver)->get();

    return $deliveryOrder;
  }

  public function getPPhAllowance($id, $month, $year)
  {
    $employee_allowance = EmployeeAllowance::with('allowance')->where('status', 1)->where('month', $month)->where('year', $year)->where('employee_id', $id)->whereHas('allowance', function ($q) {
      $q->where('category', 'like', 'potonganPph');
    })->first();

    return $employee_allowance ? true : false;
  }

  public function generateByDepartment($department, $month, $year, $user)
  {
    $employee = Employee::select('employees.*')->leftJoin('departments', 'departments.id', '=', 'employees.department_id')->where('employees.status', 1);
    $string = '';
    foreach ($department as $dept) {
      $string .= "departments.path like '%$dept%'";
      if (end($department) != $dept) {
        $string .= ' or ';
      }
    }
    $employee->whereRaw('(' . $string . ')');
    $employees = $employee->get();
    if ($employees->count() > 0) {
      foreach ($employees as $employee) {
        $exists = $this->check_periode($month, $year, $employee->id);
        if ($exists) {
          $delete = $exists->delete();
        }

        $period = changeDateFormat('Y-m-d', 01 . '-' . $month . '-' . $year);

        $salaryreport = SalaryReport::create([
          'id'            => $this->getLatestId(),
          'employee_id'   => $employee->id,
          'created_by'    => $user,
          'period'        => $period,
          'status'        => -1
        ]);

        if ($salaryreport) {
          $basesalary = $this->get_employee_salary($employee->id);
          $allowance = $this->get_additional_allowance($employee->id, $month, $year);
          $alphaPenalty = $this->getAlphaData($employee->id, $month, $year);
          $deduction = $this->get_deduction($employee->id, $month, $year);
          $overtime = $this->get_overtime($employee->id, $month, $year);
          $attendance = $this->get_attendance($employee->id, $month, $year);
          $driverallowance = $this->get_driver_allowance($employee->id, $month, $year);
          $leave = $this->get_leave($employee->id, $month, $year);
          $ptkp = $this->get_pkp($employee->ptkp);
          $alpha = $this->get_alpha($employee->id, $month, $year);
          $attendance_allowance = $this->get_attendance_allowance($employee->id, $month, $year);
          $pph = $this->getPPhAllowance($employee->id, $month, $year);
          if ($basesalary) {
            $periode_salary = changeDateFormat('Y-m', $year . '-' . $month);
            $join_date = changeDateFormat('Y-m', $employee->join_date);
            $daily_salary = $basesalary->amount / 30;
            $attendance_count = count($attendance);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Basic Salary',
              'total'             => $basesalary->amount,
              'type'              => 1,
              'status'            => $basesalary->amount == 0 ? 'Hourly' : 'Monthly'
            ]);
            $salaryreport->salary_type = $basesalary->amount == 0 ? 'Hourly' : 'Monthly';
            $salaryreport->save();
          }
          if ($allowance) {
            foreach ($allowance as $key => $value) {
              if ($value->value) {
                SalaryReportDetail::create([
                  'salary_report_id'  => $salaryreport->id,
                  'employee_id'       => $employee->id,
                  'description'       => $value->description,
                  'total'             => ($value->type == 'percentage') ? $basesalary->amount * ($value->value / 100) : $value->factor * $value->value,
                  'type'              => 1,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($deduction) {
            foreach ($deduction as $key => $value) {
              if ($value->value) {
                SalaryReportDetail::create([
                  'salary_report_id'  => $salaryreport->id,
                  'employee_id'       => $employee->id,
                  'description'       => $value->description,
                  'total'             => ($value->type == 'percentage') ? $basesalary->amount * ($value->value / 100) : $value->factor * $value->value,
                  'type'              => 0,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($overtime && $employee->overtime == 'yes') {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Overtime',
              'total'             => $overtime,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($employee->join == 'yes') {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan SPSI',
              'total'             => 20000,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          if (strpos($employee->department->path, 'Driver') !== false && $driverallowance > 0) {
            $spsi = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Driver Allowance',
              'total'             => $driverallowance,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($attendance_allowance) {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Tunjangan Kehadiran',
              'total'             => $attendance_allowance,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Basic') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan absen',
              'total'             => -1 * ($alpha * ($basesalary->amount / 30)),
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Gross') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan absen',
              'total'             => -1 * ($alpha * ($salaryreport->gross_salary / 30)),
              'type'              => 1,
              'status'            => 'Draft'
            ]);
            $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
            $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
            $salaryreport->save();
          }
          if ($pph) {
            if (!$ptkp) {
              return array(
                'status'    => false,
                'message'   => 'PTKP for this employee name ' . $employee->name . ' not found. Please set PTKP or uncheck PPh 21 allowance for this generate month.'
              );
            }
            $gross = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
            $deduction = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
            $position_allowance = $gross * (5 / 100);
            $new_net = $gross - $position_allowance;
            $salary_year = $new_net * 12;
            $pkp = $salary_year - $ptkp->value;
            $pkp_left = $pkp;
            $pph21 = 0;
            $iteration = 4;
            if ($employee->npwp) {
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100));
                  } else {
                    break;
                  }
                }
              }
            }else{
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100) * (120/100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100) * (120/100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100) * (120/100));
                  } else {
                    break;
                  }
                }
              }
            }
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Biaya Jabatan',
              'total'             => ($position_allowance) > 0 ? $position_allowance : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Net Salary (Yearly)',
              'total'             => ($salary_year) > 0 ? $salary_year : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'PPh 21 (Yearly)',
              'total'             => ($pph21) > 0 ? $pph21 : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan PPh 21',
              'total'             => ($pph21 / 12) > 0 ? $pph21 / 12 : 0,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
        } else {
          return array(
            'status'    => false,
            'message'   => $salaryreport
          );
        }
      }
    } else {
      return array(
        'status'    => true,
        'message'   => "Data not found"
      );
    }
    return array(
      'status'    => true,
      'message'   => "salary report generated by department successfully"
    );
  }

  public function generateByPosition($position, $month, $year, $user)
  {
    $employees = Employee::select('employees.*')->whereIn('title_id', $position)->where('employees.status', 1)->get();
    if (!$employees->isEmpty()) {
      foreach ($employees as $employee) {
        $exists = $this->check_periode($month, $year, $employee->id);
        if ($exists) {
          $delete = $exists->delete();
        }

        $period = changeDateFormat('Y-m-d', 01 . '-' . $month . '-' . $year);

        $salaryreport = SalaryReport::create([
          'id'            => $this->getLatestId(),
          'employee_id'   => $employee->id,
          'created_by'    => $user,
          'period'        => $period,
          'status'        => -1
        ]);
        if ($salaryreport) {
          $basesalary = $this->get_employee_salary($employee->id);
          $allowance = $this->get_additional_allowance($employee->id, $month, $year);
          $alphaPenalty = $this->getAlphaData($employee->id, $month, $year);
          $deduction = $this->get_deduction($employee->id, $month, $year);
          $overtime = $this->get_overtime($employee->id, $month, $year);
          $attendance = $this->get_attendance($employee->id, $month, $year);
          $leave = $this->get_leave($employee->id, $month, $year);
          $driverallowance = $this->get_driver_allowance($employee->id, $month, $year);
          $ptkp = $this->get_pkp($employee->ptkp);
          $alpha = $this->get_alpha($employee->id, $month, $year);
          $attendance_allowance = $this->get_attendance_allowance($employee->id, $month, $year);
          $pph = $this->getPPhAllowance($employee->id, $month, $year);
          if ($basesalary) {
            $periode_salary = changeDateFormat('Y-m', $year . '-' . $month);
            $join_date = changeDateFormat('Y-m', $employee->join_date);
            $daily_salary = $basesalary->amount / 30;
            $attendance_count = count($attendance);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Basic Salary',
              'total'             => $basesalary->amount,
              'type'              => 1,
              'status'            => $basesalary->amount == 0 ? 'Hourly' : 'Monthly'
            ]);
            $salaryreport->salary_type = $basesalary->amount == 0 ? 'Hourly' : 'Monthly';
            $salaryreport->save();
          }
          if ($allowance) {
            foreach ($allowance as $key => $value) {
              if ($value->value) {
                SalaryReportDetail::create([
                  'salary_report_id'  => $salaryreport->id,
                  'employee_id'       => $employee->id,
                  'description'       => $value->description,
                  'total'             => ($value->type == 'percentage') ? $basesalary->amount * ($value->value / 100) : $value->factor * $value->value,
                  'type'              => 1,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($deduction) {
            foreach ($deduction as $key => $value) {
              if ($value->value) {
                SalaryReportDetail::create([
                  'salary_report_id'  => $salaryreport->id,
                  'employee_id'       => $employee->id,
                  'description'       => $value->description,
                  'total'             => ($value->type == 'percentage') ? $basesalary->amount * ($value->value / 100) : $value->factor * $value->value,
                  'type'              => 0,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($overtime && $employee->overtime == 'yes') {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Overtime',
              'total'             => $overtime,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($employee->join == 'yes') {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan SPSI',
              'total'             => 20000,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          if (strpos($employee->department->path, 'Driver') !== false && $driverallowance > 0) {
            $spsi = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Driver Allowance',
              'total'             => $driverallowance,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($attendance_allowance) {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Tunjangan Kehadiran',
              'total'             => $attendance_allowance,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Basic') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan absen',
              'total'             => -1 * ($alpha * ($basesalary->amount / 30)),
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Gross') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan absen',
              'total'             => -1 * ($alpha * ($salaryreport->gross_salary / 30)),
              'type'              => 1,
              'status'            => 'Draft'
            ]);
            $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
            $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
            $salaryreport->save();
          }
          if ($pph) {
            if (!$ptkp) {
              return array(
                'status'    => false,
                'message'   => 'PTKP for this employee name ' . $employee->name . ' not found. Please set PTKP or uncheck PPh 21 allowance for this generate month.'
              );
            }
            $gross = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
            $deduction = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
            $position_allowance = $gross * (5 / 100);
            $new_net = $gross - $position_allowance;
            $salary_year = $new_net * 12;
            $pkp = $salary_year - $ptkp->value;
            $pkp_left = $pkp;
            $pph21 = 0;
            $iteration = 4;
            if ($employee->npwp) {
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100));
                  } else {
                    break;
                  }
                }
              }
            }else{
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100) * (120/100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100) * (120/100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100) * (120/100));
                  } else {
                    break;
                  }
                }
              }
            }
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Biaya Jabatan',
              'total'             => ($position_allowance) > 0 ? $position_allowance : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Net Salary (Yearly)',
              'total'             => ($salary_year) > 0 ? $salary_year : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'PPh 21 (Yearly)',
              'total'             => ($pph21) > 0 ? $pph21 : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan PPh 21',
              'total'             => ($pph21 / 12) > 0 ? $pph21 / 12 : 0,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
        } else {
          return $salaryreport;
        }
      }
      return array(
        'status'    => true,
        'message'   => "salary report generated successfully"
      );
    } else {
      return array(
        'status'    => false,
        'message'   => "This position has no employees"
      );
    }
  }

  public function generateByWorkgroup($workgroup, $month, $year, $user)
  {
    $employees = Employee::select('employees.*')->whereIn('workgroup_id', $workgroup)->where('employees.status', 1)->get();
    if (!$employees->isEmpty()) {
      foreach ($employees as $employee) {
        $exists = $this->check_periode($month, $year, $employee->id);
        if ($exists) {
          $delete = $exists->delete();
        }

        $period = changeDateFormat('Y-m-d', 01 . '-' . $month . '-' . $year);

        $salaryreport = SalaryReport::create([
          'id'            => $this->getLatestId(),
          'employee_id'   => $employee->id,
          'created_by'    => $user,
          'period'        => $period,
          'status'        => -1
        ]);
        if ($salaryreport) {
          $basesalary = $this->get_employee_salary($employee->id);
          $allowance = $this->get_additional_allowance($employee->id, $month, $year);
          $alphaPenalty = $this->getAlphaData($employee->id, $month, $year);
          $deduction = $this->get_deduction($employee->id, $month, $year);
          $overtime = $this->get_overtime($employee->id, $month, $year);
          $attendance = $this->get_attendance($employee->id, $month, $year);
          $leave = $this->get_leave($employee->id, $month, $year);
          $alpha = $this->get_alpha($employee->id, $month, $year);
          $driverallowance = $this->get_driver_allowance($employee->id, $month, $year);
          $ptkp = $this->get_pkp($employee->ptkp);
          $attendance_allowance = $this->get_attendance_allowance($employee->id, $month, $year);
          $pph = $this->getPPhAllowance($employee->id, $month, $year);
          if ($basesalary) {
            $periode_salary = changeDateFormat('Y-m', $year . '-' . $month);
            $join_date = changeDateFormat('Y-m', $employee->join_date);
            $daily_salary = $basesalary->amount / 30;
            $attendance_count = count($attendance);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Basic Salary',
              'total'             => $basesalary->amount,
              'type'              => 1,
              'status'            => $basesalary->amount == 0 ? 'Hourly' : 'Monthly'
            ]);
            $salaryreport->salary_type = $basesalary->amount == 0 ? 'Hourly' : 'Monthly';
            $salaryreport->save();
          }
          if ($allowance) {
            foreach ($allowance as $key => $value) {
              if ($value->value) {
                SalaryReportDetail::create([
                  'salary_report_id'  => $salaryreport->id,
                  'employee_id'       => $employee->id,
                  'description'       => $value->description,
                  'total'             => ($value->type == 'percentage') ? $basesalary->amount * ($value->value / 100) : $value->factor * $value->value,
                  'type'              => 1,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($deduction) {
            foreach ($deduction as $key => $value) {
              if ($value->value) {
                SalaryReportDetail::create([
                  'salary_report_id'  => $salaryreport->id,
                  'employee_id'       => $employee->id,
                  'description'       => $value->description,
                  'total'             => ($value->type == 'percentage') ? $basesalary->amount * ($value->value / 100) : $value->factor * $value->value,
                  'type'              => 0,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($overtime && $employee->overtime == 'yes') {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Overtime',
              'total'             => $overtime,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($employee->join == 'yes') {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan SPSI',
              'total'             => 20000,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          if (strpos($employee->department->path, 'Driver') !== false && $driverallowance > 0) {
            $spsi = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Driver Allowance',
              'total'             => $driverallowance,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($attendance_allowance) {
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Tunjangan Kehadiran',
              'total'             => $attendance_allowance,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Basic') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan absen',
              'total'             => -1 * ($alpha * ($basesalary->amount / 30)),
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Gross') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan absen',
              'total'             => -1 * ($alpha * ($salaryreport->gross_salary / 30)),
              'type'              => 1,
              'status'            => 'Draft'
            ]);
            $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
            $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
            $salaryreport->save();
          }
          if ($pph) {
            if (!$ptkp) {
              return array(
                'status'    => false,
                'message'   => 'PTKP for this employee name ' . $employee->name . ' not found. Please set PTKP or uncheck PPh 21 allowance for this generate month.'
              );
            }
            $gross = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
            $deduction = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
            $position_allowance = $gross * (5 / 100);
            $new_net = $gross - $position_allowance;
            $salary_year = $new_net * 12;
            $pkp = $salary_year - $ptkp->value;
            $pkp_left = $pkp;
            $pph21 = 0;
            $iteration = 4;
            if ($employee->npwp) {
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100));
                  } else {
                    break;
                  }
                }
              }
            }else{
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100) * (120/100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100) * (120/100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100) * (120/100));
                  } else {
                    break;
                  }
                }
              }
            }
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Biaya Jabatan',
              'total'             => ($position_allowance) > 0 ? $position_allowance : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Net Salary (Yearly)',
              'total'             => ($salary_year) > 0 ? $salary_year : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'PPh 21 (Yearly)',
              'total'             => ($pph21) > 0 ? $pph21 : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan PPh 21',
              'total'             => ($pph21 / 12) > 0 ? $pph21 / 12 : 0,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          $salaryreport->gross_salary = $this->gross_salary($salaryreport->id) ? $this->gross_salary($salaryreport->id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($salaryreport->id) ? $this->deduction_salary($salaryreport->id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
        } else {
          return $salaryreport;
        }
      }
      return array(
        'status'    => true,
        'message'   => "salary report generated successfully"
      );
    } else {
      return array(
        'status'    => false,
        'message'   => "This workgroup has no employees"
      );
    }
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    // dd($request->employee_name);
    if ($request->department && !$request->position && !$request->workgroup_id && !$request->employee_name) {
      $departments = $this->generateByDepartment($request->department, $request->montly, $request->year, $request->user);
      if (!$departments['status']) {
        return response()->json([
          'status'    => false,
          'message'   => $departments['message']
        ], 400);
      } else {
        return response()->json([
          'status'    => true,
          'message'   => $departments['message']
        ], 200);
      }
    } elseif (!$request->department && $request->position && !$request->workgroup_id && !$request->employee_name) {
      $position = $this->generateByPosition($request->position, $request->montly, $request->year, $request->user);
      if (!$position['status']) {
        return response()->json([
          'status'    => false,
          'message'   => $position['message']
        ], 400);
      } else {
        return response()->json([
          'status'    => true,
          'message'   => $position['message']
        ], 200);
      }
    } elseif (!$request->department && !$request->position && $request->workgroup_id && !$request->employee_name) {
      $workgroup = $this->generateByWorkgroup($request->workgroup_id, $request->montly, $request->year, $request->user);
      if (!$workgroup['status']) {
        return response()->json([
          'status'    => false,
          'message'   => $workgroup['message']
        ], 400);
      } else {
        return response()->json([
          'status'    => true,
          'message'   => $workgroup['message']
        ], 200);
      }
    } elseif (!$request->department && !$request->position && !$request->workgroup_id && $request->employee_name) {
      DB::beginTransaction();
      
      foreach ($request->employee_name as $view_employee) {
        $exists = $this->check_periode($request->montly, $request->year, $view_employee);
        if ($exists) {
          $delete = $exists->delete();
        }
  
        $period = changeDateFormat('Y-m-d', 01 . '-' . $request->montly . '-' . $request->year);
  
        $id = $this->getLatestId();
        $salaryreport = SalaryReport::create([
          'id'            => $id,
          'employee_id'   => $view_employee,
          'created_by'    => $request->user,
          'period'        => $period,
          'status'        => -1
        ]);
        if ($salaryreport) {
          $basesalary = $this->get_employee_salary($view_employee);
          $alphaPenalty = $this->getAlphaData($view_employee, $request->montly, $request->year);
          $allowance = $this->get_additional_allowance($view_employee, $request->montly, $request->year);
          $deduction = $this->get_deduction($view_employee, $request->montly, $request->year);
          $overtime = $this->get_overtime($view_employee, $request->montly, $request->year);
          $attendance = $this->get_attendance($view_employee, $request->montly, $request->year);
          $attendance_dayoff = $this->get_dayoff_attendance($view_employee, $request->montly, $request->year);
          $attendance_maxoff = $this->get_maxoff($view_employee, $request->montly, $request->year);
          $leave = $this->get_leave($view_employee, $request->montly, $request->year);
          $alpha = $this->get_alpha($view_employee, $request->montly, $request->year);
          $driverallowance = $this->get_driver_allowance($view_employee, $request->montly, $request->year);
          $employee = Employee::with('department')->with('title')->find($view_employee);
          $ptkp = $this->get_pkp($employee->ptkp);
          $attendance_allowance = $this->get_attendance_allowance($view_employee, $request->montly, $request->year);
          $pph = $this->getPPhAllowance($view_employee, $request->montly, $request->year);
          if ($basesalary) {
            $periode_salary = changeDateFormat('Y-m', $request->year . '-' . $request->montly);
            $join_date = changeDateFormat('Y-m', $employee->join_date);
            $daily_salary = $basesalary->amount / 30;
            $attendance_count = count($attendance);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Basic Salary',
              'total'             => $basesalary->amount,
              'type'              => 1,
              'status'            => $basesalary->amount == 0 ? 'Hourly' : 'Monthly'
            ]);
            $salaryreport->salary_type = $basesalary->amount == 0 ? 'Hourly' : 'Monthly';
            $salaryreport->save();
          } else {
            DB::rollBack();
            return response()->json([
              'status'    => false,
              'message'   => 'Base salary for this employee not found'
            ], 400);
          }
          if ($allowance) {
            foreach ($allowance as $key => $value) {
              if ($value->value && $value->factor) {
                $total = $value->factor * $value->value;
                if($value->description == 'Bonus Performa (KPI)'){
                  $cek = $value->factor;  
                  if($cek >= 0.7 && $cek <= 0.79){
                    $total = 100000;
                  }elseif($cek >= 0.8 && $cek <= 1){
                    $total = 150000;
                  }else{
                    $total = 0;
                  }
                }
                SalaryReportDetail::create([
                  'salary_report_id'  => $id,
                  'employee_id'       => $view_employee,
                  'description'       => $value->description,
                  'total'             => $total,
                  'type'              => 1,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($deduction) {
            foreach ($deduction as $key => $value) {
              if ($value->value) {
                SalaryReportDetail::create([
                  'salary_report_id'  => $id,
                  'employee_id'       => $view_employee,
                  'description'       => $value->description,
                  'total'             => ($value->type == 'percentage') ? $basesalary->amount * ($value->value / 100) : $value->factor * $value->value,
                  'type'              => 0,
                  'status'            => 'Draft'
                ]);
              } else {
                continue;
              }
            }
          }
          if ($overtime && $employee->overtime == 'yes') {
            SalaryReportDetail::create([
              'salary_report_id'  => $id,
              'employee_id'       => $view_employee,
              'description'       => 'Overtime',
              'total'             => $overtime,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($employee->join == 'yes') {
            $spsi = SalaryReportDetail::create([
              'salary_report_id'  => $id,
              'employee_id'       => $view_employee,
              'description'       => 'Potongan SPSI',
              'total'             => 20000,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          if (strpos($employee->department->path, 'Driver') !== false && $driverallowance > 0) {
            $spsi = SalaryReportDetail::create([
              'salary_report_id'  => $id,
              'employee_id'       => $view_employee,
              'description'       => 'Driver Allowance',
              'total'             => $driverallowance,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Basic') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $id,
              'employee_id'       => $view_employee,
              'description'       => 'Potongan absen',
              'total'             => -1 * $alphaPenalty,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
          }
          if ($attendance_allowance) {
            if ($attendance_dayoff <= $attendance_maxoff) {
              SalaryReportDetail::create([
                'salary_report_id'  => $id,
                'employee_id'       => $view_employee,
                'description'       => 'Tunjangan Kehadiran',
                'total'             => $attendance_allowance,
                'type'              => 1,
                'status'            => 'Draft'
              ]);
            }
          }
          $salaryreport->gross_salary = $this->gross_salary($id) ? $this->gross_salary($id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($id) ? $this->deduction_salary($id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
          if ($alphaPenalty > 0 && $employee->workgroup->penalty == 'Gross') {
            $alpha_penalty = SalaryReportDetail::create([
              'salary_report_id'  => $id,
              'employee_id'       => $view_employee,
              'description'       => 'Potongan absen',
              'total'             => -1 * $alphaPenalty,
              'type'              => 1,
              'status'            => 'Draft'
            ]);
            $salaryreport->gross_salary = $this->gross_salary($id) ? $this->gross_salary($id) : 0;
            $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
            $salaryreport->save();
          }
          if ($pph) {
            if (!$ptkp) {
              DB::rollBack();
              return response()->json([
                'status'    => false,
                'message'   => 'PTKP for this employee name ' . $employee->name . ' not found. Please set PTKP or uncheck PPh 21 allowance for this generate month.'
              ], 400);
            }
            $gross = $this->gross_salary($id) ? $this->gross_salary($id) : 0;
            $deduction = $this->deduction_salary($id) ? $this->deduction_salary($id) : 0;
            $position_allowance = $gross * (5 / 100);
            $new_net = $gross - $position_allowance;
            $salary_year = $new_net * 12;
            $pkp = $salary_year - $ptkp->value;
            $pkp_left = $pkp;
            $pph21 = 0;
            $iteration = 4;
            if ($employee->npwp) {
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100));
                  } else {
                    break;
                  }
                }
              }
            }else{
              for ($i = 1; $i <= $iteration; $i++) {
                if ($i == 1) {
                  if ($pkp_left > 0) {
                    if ($pkp_left <= 50000000) {
                      $pph21 = $pkp_left * (5 / 100) * (120/100);
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    } else {
                      $pph21 = 50000000 * (5 / 100) * (120/100) ;
                      $pkp_left = ($pkp_left - 50000000) <= 0 ? 0 : $pkp_left - 50000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 2) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 250000000) {
                      $pph21 = $pph21 + (250000000 * (15 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (15 / 100)* (120/100));
                      $pkp_left = ($pkp_left - 250000000) <= 0 ? 0 : $pkp_left - 250000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 3) {
                  if ($pkp_left > 0) {
                    if ($pkp_left >= 500000000) {
                      $pph21 = $pph21 + (500000000 * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    } else {
                      $pph21 = $pph21 + ($pkp_left * (25 / 100) * (120/100));
                      $pkp_left = ($pkp_left - 500000000) <= 0 ? 0 : $pkp_left - 500000000;
                    }
                  } else {
                    break;
                  }
                }
                if ($i == 4) {
                  if ($pkp_left > 0) {
                    $pph21 = $pph21 + ($pkp_left * (30 / 100) * (120/100));
                  } else {
                    break;
                  }
                }
              }
            }
  
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Biaya Jabatan',
              'total'             => $position_allowance > 0 ? $position_allowance : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Net Salary (Yearly)',
              'total'             => $salary_year > 0 ? $salary_year : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'PPh 21 (Yearly)',
              'total'             => ($pph21) > 0 ? $pph21 : 0,
              'type'              => 2,
              'status'            => 'Draft'
            ]);
            SalaryReportDetail::create([
              'salary_report_id'  => $salaryreport->id,
              'employee_id'       => $employee->id,
              'description'       => 'Potongan PPh 21',
              'total'             => ($pph21 / 12) > 0 ? $pph21 / 12 : 0,
              'type'              => 0,
              'status'            => 'Draft'
            ]);
          }
          $salaryreport->gross_salary = $this->gross_salary($id) ? $this->gross_salary($id) : 0;
          $salaryreport->deduction    = $this->deduction_salary($id) ? $this->deduction_salary($id) : 0;
          $salaryreport->net_salary   = $salaryreport->gross_salary - $salaryreport->deduction;
          $salaryreport->save();
        } elseif (!$salaryreport) {
          DB::rollBack();
          return response()->json([
            'status'    => false,
            'message'   => $salaryreport
          ], 400);
        }
      }
      DB::commit();
      return response()->json([
        'status'    => true,
        'message'   => 'salary report generated successfully',
      ], 200);
    } else {
      return response()->json([
        'status'    => false,
        'message'   => 'Please select one parameter from position, department or workgroup to generate mass'
      ], 400);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    $salary_detail = SalaryReport::with('salarydetail')->with('employee')->find($id);
    $employee = Employee::with('department')->with('title')->find($salary_detail->employee_id);
    if ($salary_detail) {
      return view('admin.salaryreport.detail', compact('salary_detail', 'employee'));
    } else {
      abort(404);
    }
  }

  public function editapproval($id)
  {
    $salary_detail = SalaryReport::with('salarydetail')->with('employee')->find($id);
    $employee = Employee::with('department')->with('title')->find($salary_detail->employee_id);
    if ($salary_detail) {
      return view('admin.salaryreport.editapproval', compact('salary_detail', 'employee'));
    } else {
      abort(404);
    }
  }

  public function updateapprove(Request $request, $id)
  {
    $salaryreport = SalaryReport::find($id);
    $salaryreport->status = $request->status;
    $salaryreport->save();

    if (!$salaryreport) {
      return response()->json([
        'status'    => false,
        'message'   => $salaryreport
      ], 400);
    }

    // return view('admin.salaryreport.indexapproval');
    return response()->json([
      'status'    => true,
      'results'   => route('salaryreport.indexapproval')
    ], 200);
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
   * Print mass all salary that checked
   */
  public function printmass(Request $request)
  {
    $id = json_decode($request->id);
    $salaries = SalaryReport::with('employee')->with('salarydetail')->whereIn('id', $id)->get();
    foreach ($salaries as $salary) {
      $salary->print_status = 1;
      $salary->save();
    }
    return view('admin.salaryreport.print', compact('salaries'));
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    try {
      $report = SalaryReport::find($id);
      $report->delete();
    } catch (\Illuminate\Database\QueryException $e) {
      return response()->json([
        'status'    => false,
        'message'   => 'Data has been used to another page'
      ], 400);
    }
    return response()->json([
      'status'    => true,
      'message'   => 'Success delete data'
    ], 200);
  }

  public function print_pdf()
  {
    return view('admin.salaryreport.report');
  }

  public function pdf($id)
  {
    $report = SalaryReport::with('salarydetail')->with('employee')->find($id);
    $employee = Employee::with('department')->with('title')->find($report->employee_id);
    return view('admin.salaryreport.report', compact('employee', 'report'));
    // $data = ['title' => 'Slip Gaji PT.Bosung Indonesia'];

    // $pdf = PDF::loadView('admin/salaryreport/report', compact('report', 'employee'));
    // $pdf->setPaper('A4', 'landscape');
    // set_time_limit(5000);
    // return $pdf->download('Salary-Report.pdf');
  }
  public function exportsalary(Request $request)
  {
    $employee = $request->name;
    $department = $request->department;
    $workgroup = $request->workgroup_id;
    $month = $request->montly;
    $year = $request->year;

    $object = new \PHPExcel();
    $object->getProperties()->setCreator('Bosung Indonesia');
    $object->setActiveSheetIndex(0);
    $sheet = $object->getActiveSheet();

    $salary = SalaryReport::select(
      'salary_reports.*',
      'salary_reports.employee_id',
      'work_groups.name as workgroup_name',
      'departments.name as department_name',
      'employees.nid as nik',
      'employees.name as employee_name',
      'employees.account_bank as account_name',
      'employees.account_no as account_no'
    );
    $salary->leftJoin('employees', 'employees.id', '=', 'salary_reports.employee_id');
    $salary->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
    $salary->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
    $salary->whereMonth('salary_reports.period', $month)->whereYear('salary_reports.period', $year);
    if ($department) {
      $string = '';
      foreach ($department as $dept) {
        $string .= "departments.path like '%$dept%'";
        if (end($department) != $dept) {
          $string .= ' or ';
        }
      }
      $salary->whereRaw('(' . $string . ')');
    }
    if ($workgroup) {
      $salary->whereIn('employees.workgroup_id', $workgroup);
    }
    $salary_reports = $salary->get();

    // Get Additional Allowance
    $query = Allowance::leftJoin('allowance_categories', 'allowance_categories.key', '=', 'allowances.category');
    $query->where('allowance_categories.type', '=', 'additional');
    $query->orderBy('allowances.allowance', 'asc');
    $additionals = $query->get();
    $addition_name = [];

    // Get Deduction Allowance
    $query = Allowance::leftJoin('allowance_categories', 'allowance_categories.key', '=', 'allowances.category');
    $query->where('allowance_categories.type', '=', 'deduction');
    $query->orderBy('allowances.allowance', 'asc');
    $deductions = $query->get();
    $deduction_name = [];

    // Header Columne Excel
    $sheet->mergeCells('A1:F1');
    $sheet->setCellValue('A1', 'Personal Data')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('A2', 'Workgroup Combination');
    $sheet->setCellValue('B2', 'Department');
    $sheet->setCellValue('C2', 'NIK');
    $sheet->setCellValue('D2', 'Nama');
    $sheet->setCellValue('E2', 'Bank Account Name');
    $sheet->setCellValue('F2', 'Bank Account Number');
    $column_number = 7;
    $sheet->setCellValue('G1', 'Additional')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->setCellValue('G2', 'Basic Salary');
    foreach ($additionals as $key => $additional) {
      $sheet->setCellValueByColumnAndRow($column_number, 2, $additional->allowance);
      $column_number++;
    }
    $sheet->setCellValueByColumnAndRow($column_number, 2, 'Overtime');
    $sheet->setCellValueByColumnAndRow(++$column_number, 2, 'Driver Allowance');
    $sheet->setCellValueByColumnAndRow(++$column_number, 2, 'Potongan absen');
    $sheet->setCellValueByColumnAndRow(++$column_number, 2, 'Tunjangan Kehadiran');
    $sheet->mergeCellsByColumnAndRow(6, 1, $column_number, 1);
    $sheet->setCellValueByColumnAndRow(++$column_number, 1, 'Gross Salary')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCellsByColumnAndRow($column_number, 1, $column_number, 2);
    $sheet->setCellValueByColumnAndRow(++$column_number, 1, 'Deduction')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $col_deduction = $column_number;
    foreach ($deductions as $key => $deduction) {
      $sheet->setCellValueByColumnAndRow($column_number, 2, $deduction->allowance);
      $column_number++;
    }
    $sheet->setCellValueByColumnAndRow($column_number, 2, 'Potongan SPSI');
    $sheet->mergeCellsByColumnAndRow($col_deduction, 1, $column_number, 1);
    $sheet->setCellValueByColumnAndRow(++$column_number, 1, 'Deduction Salary')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCellsByColumnAndRow($column_number, 1, $column_number, 2);
    $sheet->setCellValueByColumnAndRow(++$column_number, 1, 'Net Salary')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCellsByColumnAndRow($column_number, 1, $column_number, 2);
    $sheet->setCellValueByColumnAndRow(++$column_number, 1, 'WT')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCellsByColumnAndRow($column_number, 1, $column_number, 2);
    $sheet->setCellValueByColumnAndRow(++$column_number, 1, 'OT')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCellsByColumnAndRow($column_number, 1, $column_number, 2);
    // $sheet->setCellValue('AM1', 'WT')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    // $sheet->mergeCellsByColumnAndRow($column_number, 1, $column_number, 2);
    // $sheet->setCellValue('AN1', 'OT')->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    // $sheet->mergeCellsByColumnAndRow($column_number, 1, $column_number, 2);
    $row_number = 3;

    foreach ($salary_reports as $reports) {
      $wt = Attendance::where('employee_id', $reports->employee_id)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->where('status', 1)->sum('adj_working_time');
      $ot = Attendance::where('employee_id', $reports->employee_id)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->where('status', 1)->sum('adj_over_time');
      $basic_salary = SalaryReportDetail::where('salary_report_id', $reports->id)->where('description', 'Basic Salary')->first();
      $overtime = SalaryReportDetail::where('salary_report_id', $reports->id)->where('description', 'Overtime')->first();
      $driver_allowance = SalaryReportDetail::where('salary_report_id', $reports->id)->where('description', 'Driver Allowance')->first();
      $penalty = SalaryReportDetail::where('salary_report_id', $reports->id)->where('description', 'Potongan absen')->first();
      $kehadiran = SalaryReportDetail::where('salary_report_id', $reports->id)->where('description', 'Tunjangan Kehadiran')->first();
      $spsi = SalaryReportDetail::where('salary_report_id', $reports->id)->where('description', 'Potongan SPSI')->first();

      $sheet->setCellValue('A' . $row_number, $reports->workgroup_name);
      $sheet->setCellValue('B' . $row_number, $reports->department_name);
      $sheet->setCellValue('C' . $row_number, $reports->nik);
      $sheet->setCellValue('D' . $row_number, $reports->employee_name);
      $sheet->setCellValue('E' . $row_number, $reports->account_name ? $reports->account_name : '-');
      $sheet->setCellValueExplicit('F' . $row_number, $reports->account_no ? $reports->account_no : '-', PHPExcel_Cell_DataType::TYPE_STRING);
      if ($basic_salary) {
        $sheet->setCellValue('G' . $row_number, $basic_salary->total);
      } else {
        $sheet->setCellValue('G' . $row_number, 0);
      }
      $col = 7;
      foreach ($additionals as $key => $name) {
        $additional = SalaryReportDetail::where('salary_report_id', $reports->id)->where('type', 1)->where('description', $name->allowance)->first();
        if ($additional) {
          $sheet->setCellValueByColumnAndRow($col, $row_number, round($additional->total));
        } else {
          $sheet->setCellValueByColumnAndRow($col, $row_number, 0);
        }
        $col++;
      }
      if ($overtime) {
        $sheet->setCellValueByColumnAndRow($col, $row_number, round($overtime->total));
      } else {
        $sheet->setCellValueByColumnAndRow($col, $row_number, 0);
      }
      if ($driver_allowance) {
        $sheet->setCellValueByColumnAndRow(++$col, $row_number, round($driver_allowance->total));
      } else {
        $sheet->setCellValueByColumnAndRow(++$col, $row_number, 0);
      }
      if ($penalty) {
        $sheet->setCellValueByColumnAndRow(++$col, $row_number, round($penalty->total));
      } else {
        $sheet->setCellValueByColumnAndRow(++$col, $row_number, 0);
      }
      if ($kehadiran) {
        $sheet->setCellValueByColumnAndRow(++$col, $row_number, round($kehadiran->total));
      } else {
        $sheet->setCellValueByColumnAndRow(++$col, $row_number, 0);
      }
      $sheet->setCellValueByColumnAndRow(++$col, $row_number, round($reports->gross_salary));
      ++$col;
      foreach ($deductions as $key => $name) {
        $deduction = SalaryReportDetail::where('salary_report_id', $reports->id)->where('type', 0)->where('description', $name->allowance)->first();
        if ($deduction) {
          $sheet->setCellValueByColumnAndRow($col, $row_number, round($deduction->total));
        } else {
          $sheet->setCellValueByColumnAndRow($col, $row_number, 0);
        }
        $col++;
      }
      if ($spsi) {
        $sheet->setCellValueByColumnAndRow($col, $row_number, $spsi->total);
      } else {
        $sheet->setCellValueByColumnAndRow($col, $row_number, 0);
      }
      $sheet->setCellValueByColumnAndRow(++$col, $row_number, round($reports->deduction));
      $sheet->setCellValueByColumnAndRow(++$col, $row_number, round($reports->net_salary));
      $sheet->setCellValueByColumnAndRow(++$col, $row_number, $wt);
      $sheet->setCellValueByColumnAndRow(++$col, $row_number, $ot ? $ot : 0);
      $row_number++;
    }
    foreach (range(0, $column_number) as $column) {
      $sheet->getColumnDimensionByColumn($column)->setAutoSize(true);
      $sheet->getCellByColumnAndRow($column, 1)->getStyle()->getFont()->setBold(true);
      $sheet->getCellByColumnAndRow($column, 2)->getStyle()->getFont()->setBold(true);
      $sheet->getCellByColumnAndRow($column, 1)->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    }
    $sheet->getPageSetup()->setFitToWidth(1);
    $objWriter = \PHPExcel_IOFactory::createWriter($object, 'Excel2007');
    ob_start();
    $objWriter->save('php://output');
    $export = ob_get_contents();
    ob_end_clean();
    header('Content-Type: application/json');
    if ($salary_reports->count() > 0) {
      return response()->json([
        'status'     => true,
        'name'        => 'salary-report-' . date('d-m-Y') . '.xlsx',
        'message'    => "Success Download Salary Report Data",
        'file'         => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($export)
      ], 200);
    } else {
      return response()->json([
        'status'     => false,
        'message'    => "Data not found",
      ], 400);
    }
  }
}