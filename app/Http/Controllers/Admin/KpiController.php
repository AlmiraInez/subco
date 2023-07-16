<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Models\Kpi;
use App\Models\KpiResult;
use App\Models\KpiBot;
use App\Models\Question;
use App\Models\Formula;
use App\Models\Allowance;
use App\Models\EmployeeDetailAllowance;
use App\Models\EmployeeAllowance;
use App\Models\Answer;
use App\Models\Employee;
use App\Models\WorkGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class KpiController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'kpi'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $emp = DB::table('employees');
        $emp->select('employees.*');
        $emp->where('status', 1);
        $employees = $emp->get();
        // $departments = Department::all();
        $query = DB::table('departments');
        $query->select('departments.*');
        $query->orderBy('path', 'asc');
        $departments = $query->get();
        $workgroups = WorkGroup::all();

        return view('admin.kpi.index', compact('employees', 'departments', 'workgroups'));
    }

    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $user_id        = auth()->user()->id;
        $employee_id = strtoupper(str_replace("'", "''", $request->employee_id));
        $nid = $request->nid;
        $department = $request->department ? explode(',', $request->department) : null;
        $workgroup = $request->workgroup ? explode(',', $request->workgroup) : null;
        $overtime = $request->overtime;
        $month = $request->month;
        $year = $request->year;
        $from = $request->from ? Carbon::parse($request->from)->startOfDay()->toDateTimeString() : null;
        $to = $request->to ? Carbon::parse($request->to)->endOfDay()->toDateTimeString() : null;
        // $employee_id   = $request->employee_id;
        // $date           = explode(' - ', $request->date);
        // $to             = Carbon::parse($date[1])->toDateString();
        // $from           = Carbon::parse($date[0])->toDateString();

        //Count Data
        //old query
        // $query = KpiResult::with('employee');
        // ->where('kpi_results.employee_id', $employee_id);
        // $query->whereBetween('date', [$from, $to]);
        //end old query
        $query = DB::table('kpi_results');
        $query->select('kpi_results.*','employees.user_id', 'employees.name as employee', 'employees.nik as nik');
        $query->leftJoin('employees','employees.id','=','kpi_results.employee_id');
        $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
        $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
        $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
        $query->where('employees.status', 1);
        if ($user_id != 1) {
            $query->where('employees.user_id', $user_id);
        }
        if ($from && $to) {
            $query->whereBetween('kpi_results.result_date', [$from, $to]);
        }
        if (!$from && $to) {
            $query->where('kpi_results.result_date', '<=', $to);
        }
        if ($employee_id) {
            $query->whereRaw("upper(employees.name) like '%$employee_id%'");
        }
        if ($nid) {
            $query->whereRaw("employees.nid like '%$nid%'");
        }
        if ($department) {
            $string = '';
            foreach ($department as $dept) {
                $string .= "departments.path like '%$dept%'";
                if (end($department) != $dept) {
                    $string .= ' or ';
                }
            }
            $query->whereRaw('(' . $string . ')');
        }
        if ($workgroup) {
            $query->whereIn('employees.workgroup_id', $workgroup);
        }
        $query->orderBy('kpi_results.created_at', 'desc');
        $recordsTotal = $query->count();

        //Select Pagination
        //old query
        //$query = KpiResult::with('employee')
        //->select('kpi_results.*');
        // ->where('kpi_results.employee_id', $employee_id);
        // $query->whereBetween('date', [$from, $to]);
        // end old query
        $query = DB::table('kpi_results');
        $query->select('kpi_results.*','employees.user_id', 'employees.name as employee', 'employees.nik as nik');
        $query->leftJoin('employees','employees.id','=','kpi_results.employee_id');
        $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
        $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
        $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
        $query->where('employees.status', 1);
        if ($from && $to) {
            $query->whereBetween('kpi_results.result_date', [$from, $to]);
        }
        if (!$from && $to) {
            $query->where('kpi_results.result_date', '<=', $to);
        }
        if ($employee_id) {
            $query->whereRaw("upper(employees.name) like '%$employee_id%'");
        }
        if ($nid) {
            $query->whereRaw("employees.nid like '%$nid%'");
        }
        if ($department) {
            $string = '';
            foreach ($department as $dept) {
                $string .= "departments.path like '%$dept%'";
                if (end($department) != $dept) {
                    $string .= ' or ';
                }
            }
            $query->whereRaw('(' . $string . ')');
        }
        if ($workgroup) {
            $query->whereIn('employees.workgroup_id', $workgroup);
        }
        if ($user_id != 1) {
            $query->where('employees.user_id', $user_id);
        }
        $query->orderBy('kpi_results.created_at', 'desc');
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $results = $query->get();

        $data = [];
        foreach ($results as $result) {
            $result->no = ++$start;
            $data[] = $result;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }
    public function draft()
    {
        return view('admin.kpi.draft');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $employee_id = $request->input('employee_id');
        $result_date = date('Y-m-d',strtotime(dbDate($request->input('result_date'))));
        $employee = Employee::where('id', $employee_id)->first();
        $kpiresult = KpiResult::where('result_date', $result_date)->where('employee_id', $employee->id)->first();
        if (!$kpiresult) {
            $department_id = $employee->department_id;
            //dd($department_id);
            // $site_id = $employee->site_id;
            $questions = Question::select('questions.*')
            ->leftJoin('question_departments', 'question_departments.question_id', '=', 'questions.id')
            ->where('department_id', $department_id)
                ->orderBy('order', 'asc')
                ->get();
            $filters = [];
            $actions = [];
            foreach ($questions as $question) {
                if ($question->frequency == 'harian') {
                    $start_date = $question->start_date;
                    $finish_date = $question->finish_date;
                    if ($start_date && $start_date > $result_date) {
                        continue;
                    }
                    if ($finish_date && $finish_date < $result_date) {
                        continue;
                    }
                    $filters[] = $question;
                }
                if ($question->frequency == 'bulanan') {
                    $start_date = $question->start_date;
                    $finish_date = $question->finish_date;
                    if ($start_date && $start_date > $result_date) {
                        continue;
                    }
                    if ($finish_date && $finish_date < $result_date) {
                        continue;
                    }
                    if ($start_date && date('d', strtotime($start_date)) != date('d')) {
                        continue;
                    }
                    $filters[] = $question;
                }
                if ($question->frequency == 'tahunan') {
                    $start_date = $question->start_date;
                    $finish_date = $question->finish_date;
                    if ($start_date && $start_date > $result_date) {
                        continue;
                    }
                    if ($finish_date && $finish_date < $result_date) {
                        continue;
                    }
                    if ($start_date && date('m-d', strtotime($start_date)) != date('m-d')) {
                        continue;
                    }
                    $filters[] = $question;
                }
                if ($question->frequency == 'perkejadian') {
                    $start_date = $question->start_date;
                    $finish_date = $question->finish_date;
                    if ($start_date && $start_date > $result_date) {
                        continue;
                    }
                    if ($finish_date && $finish_date < $result_date) {
                        continue;
                    }
                    $kpiresult = KpiResult::where('employee_id', $employee->id)->orderBy('result_date', 'desc')->first();
                    if ($kpiresult) {
                        if ($question->answer_type == 'checkbox') {
                            $answers = Answer::where('question_id', $question->id)->get();
                            foreach ($answers as $answer) {
                                $kpis = Kpi::with('answer')->where('question_id', $question->id)->where('answer_id', $answer->id)->where('date', $kpiresult->result_date)->get();
                                foreach ($kpis as $kpi) {
                                    $actions[] = $kpi;
                                }
                            }
                        } else {
                            $kpis = Kpi::with('answer')->where('question_id', $question->id)->where('date', $kpiresult->result_date)->get();
                            foreach ($kpis as $kpi) {
                                $actions[] = $kpi;
                            }
                        }
                    }
                    if ($question->type == 'Pertanyaan' && count($actions) > 0) {
                        continue;
                    }
                    $filters[] = $question;
                }
            }
            $questions = $filters;
            $answers = Answer::orderBy('order', 'asc')->get();
            return view('admin.kpi.create', compact('questions', 'answers', 'employee', 'actions', 'employee_id','result_date'));
        } else {
            return redirect()->route('kpi.index')->withErrors(['msg' => 'Anda telah mengisi kpi untuk hari ini.']);
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
        DB::beginTransaction();
        $employee_id = $request->employee_id;
        $result_date = $request->result_date;
        // dd($employee_id);
        $employee = Employee::where('id', $employee_id)->first();
        $department_id = $employee->department_id;
        // $site_id = $workforce->site_id;
        $questions = Question::select('questions.*')
        ->leftJoin('question_departments', 'question_departments.question_id', '=', 'questions.id')
        ->where('department_id', $department_id)
            ->orderBy('order', 'asc')
            ->get();
        foreach ($questions as $question) {
            if($question->answer_type == 'checkbox'){
                if ($request->input('answer_choice_' . $question->id)) {
                    foreach ($request->input('answer_choice_' . $question->id) as $choice) {
                        $answer = Answer::find($choice);
                        if ($answer) {
                            $kpi = Kpi::create([
                                'date'           => $result_date,
                                'question_id'    => $question->id,
                                'answer_id'      => $answer->id,
                                'rating'         => $answer->rating,
                                'description'    => '',
                                'employee_id'    => $employee->id
                            ]);
                            if (!$kpi) {
                                DB::rollBack();
                                return response()->json([
                                    'status'        => false,
                                    'message'       => $kpi
                                ], 400);
                            }
                        }
                    }
                }
            }

            if($question->answer_type == 'radio'){
                if ($request->input('answer_choice_' . $question->id)) {
                    $choice = $request->input('answer_choice_' . $question->id);
                    $answer = Answer::find($choice);
                    if ($answer) {
                        $kpi = Kpi::create([
                            'date'           => $result_date,
                            'question_id'    => $question->id,
                            'answer_id'      => $answer->id,
                            'rating'         => $answer->rating,
                            'description'    => '',
                            'employee_id'    => $employee->id
                        ]);
                        if (!$kpi) {
                            DB::rollBack();
                            return response()->json([
                                'status'        => false,
                                'message'       => $kpi
                            ], 400);
                        }
                    }
                }
            }
           
        } 
        $max = 0;
        $calculate = 0;
        $answers = Answer::all();
        foreach ($answers as $answer) {
            if ($answer->question) {
                if ($request->input('answer_choice_' . $answer->question->id) == $answer->id) {
                    $calculate =  $calculate + $answer->rating;
                    $max = $max + DB::table('answers')->where('question_id',$answer->question->id)->max('rating');
                }
            } 
        }
        
        $bobot = $calculate/$max;
       
        $result = KpiResult::create([
            'result_date'       => $result_date,
            'employee_id'       => $employee->id,
            'value_total'       => $bobot,
            'status'            => 0
        ]);
        if (!$result) {
            DB::rollBack();
            return response()->json([
                'status'        => false,
                'message'       => $result
            ], 400);
        }
        $bot = KpiBot::create([
            'result_id'   => $result->id,
            'description' => $request->record,
        ]);
        if (!$bot) {
            DB::rollBack();
            return response()->json([
                'status'        => false,
                'message'       => $bot
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('kpi.index'),
        ], 200);
    }

    public function check(Request $request)
    {
        $employee_id = $request->input('employee_id');
        $employee = Employee::where('id', $employee_id)->get();
        $answer = 0;
        $calculate = 0;
        $max = 0;
        $answers = Answer::all();
        foreach ($answers as $item) {
            if ($item->question) {
                if ($request->input('answer_choice_' . $item->question->id) == $item->id) {
                    $answer++;
                    $calculate = $calculate + $item->rating;
                    $max = $max + DB::table('answers')->where('question_id',$item->question->id)->max('rating');
                }
            } 
        }
        
        
        
        return response()->json([
            'status'    => true,
            'answer'    => $answer,
            'calculate' => $calculate/$max
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Kpi  $kpi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = KpiResult::find($id);
        $employee = Employee::where('id', $result->employee_id)->first();
        if ($result) {
            $bot = KpiBot::where('result_id', $id)
                ->leftJoin('kpi_results', 'kpi_bots.result_id', '=', 'kpi_results.id')
                ->leftJoin('employees', 'kpi_results.employee_id', '=', 'employees.id')
                ->whereRaw("(kpi_results.employee_id = $employee->id or employees.id = $employee->id)")
                ->orderBy('kpi_bots.created_at', 'desc')->first();
            if ($bot) {
                return view('admin.kpi.detail', compact('bot'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }
    
    public function approvemass(Request $request)
    {
        // dd($request->approve);
        if($request->approve)
        {
            $ids =  $request->approve;
            // dd($employee_id);
            foreach($ids as $id)
            {
                $result = KpiResult::find($id);
                $result->status = 1;
                $result->save();
                // dd($id);
                $calendar = CAL_GREGORIAN;
                $bulan = date('m');
                $year = date('Y');
                $calender = DB::table('employees');
                $calender->select('calendar_exceptions.*');
                $calender->leftJoin('calendars', 'calendars.id', '=', 'employees.calendar_id');
                $calender->leftJoin('calendar_exceptions', 'calendar_exceptions.calendar_id', '=', 'calendars.id');
                $calender->where('employees.id', '=', $result->employee_id);
                $calender->whereMonth('calendar_exceptions.date_exception', $bulan);
                $calender->whereYear('calendar_exceptions.date_exception', $year);
                $total_day_off = $calender->count();
                $total_days = cal_days_in_month($calendar, $bulan, $year);
                $totalDayMonth = $total_days - $total_day_off;
                // dd($totalDayMonth);
                // dd($result);
                $readConfigs = Config::where('option','cut_off')->first();
                $cut_off = $readConfigs->value;
                if(date('d',strtotime($result->result_date)) > $cut_off){
                    $month = date('m',strtotime($result->result_date));
                    $year = date('Y',strtotime($result->result_date));
                    $month = date('m',mktime(0,0,0,$month+1,1,$year));
                    $year = date('Y',mktime(0,0,0,$month+1,1,$year));
                }else{
                    $month =  date('m', strtotime($result->result_date));
                    $year =  date('Y', strtotime($result->result_date));
                }
                $allowance = Allowance::select('id','allowance')->where('allowance','=','Bonus Performa (KPI)')->first();
                $query = EmployeeAllowance::select('employee_allowances.*');
                $query->where('employee_id', '=', $result->employee_id);
                $query->where('allowance_id', '=', $allowance->id);
                $query->where('month', $month);
                $query->where('year', $year);
                $updatefactor = $query->first();
                // dd($updatefactor);
               
                
                try {
                    $employeedetailallowance = EmployeeDetailAllowance::create([
                        'employee_id' => $result->employee_id,
                        'allowance_id' => $allowance->id,
                        'workingtime_id' => null,
                        'tanggal_masuk' => $result->result_date,
                        'value' => $result->value_total,
                        'month' => $month,
                        'year' => $year
                    ]);
                    // dd($employeedetailallowance);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status'      => false,
                        'message'     => 'There is error in employee name ' . $result->employee_id. ' when approved attendance in date ' . $result->result_date . ' and allowance ' . $allowance->allowance
                    ], 400);
                }
                if ($updatefactor) {
                    $value_total = EmployeeDetailAllowance::where('employee_id', $result->employee_id)
                        ->where('month', $month)
                        ->where('year', $year)
                        // ->where('status', 1)
                        ->sum(DB::raw('value::numeric'));

                    if (!$value_total) {
                        return response()->json([
                            'status'      => false,
                            'message'     => 'Bobot From KPI Result Null'
                        ], 400);
                    }

                    $day_total = EmployeeDetailAllowance::where('employee_id', $result->employee_id)
                        ->where('month', $month)
                        ->where('year', $year)
                        // ->where('status', 1)
                        ->get()
                        ->count();

                    if (!$day_total) {
                        return response()->json([
                            'status'      => false,
                            'message'     => 'Bobot From KPI Result Null'
                        ], 400);
                    }
                    // dd($value_total, $day_total);

                    $updatefactor->factor = $value_total / $day_total;
                    $updatefactor->save();
                }
                // dd($allowance);
                // $query = DB::table('kpi_results');
                // $query->select('kpi_results.employee_id as employee_id',
                //             'kpi_results.value_total as total',
                //             'kpi_results.result_date as date');
                // $query->where('kpi_results.id', $request->approve);
                // $employeeAllowances = $query->get();
                // // dd($employeeAllowances);
                // foreach($employeeAllowances as $employeeAllowance){
                //     if ($employeeAllowance) {
                //         // if ($employeedetailallowance) {
                            
                //         // }
                //     }
                // }
                if (!$result) {
                    return response()->json([
                        'status'     => false,
                        'message'    => "Error approve",
                    ], 200);
                }
            }
            return response()->json([
                'status'     => true,
                'message'    => "Approve successfully",
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Kpi  $kpi
     * @return \Illuminate\Http\Response
     */
    public function edit(Kpi $kpi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Kpi  $kpi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kpi $kpi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Kpi  $kpi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kpi $kpi)
    {
        //
    }
}
