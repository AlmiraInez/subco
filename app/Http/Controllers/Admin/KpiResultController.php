<?php

namespace App\Http\Controllers\Admin;

use App\Models\KpiResult;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use App\Models\WorkGroup;
use Illuminate\Support\Facades\DB;

class KpiResultController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'kpiapproval'));
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
        return view('admin.kpiapproval.index', compact('employees', 'departments', 'workgroups'));
    }
    public function read_old(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        // $employee_id   = $request->employee_id;
        // $date           = explode(' - ', $request->date);
        // $to             = Carbon::parse($date[1])->toDateString();
        // $from           = Carbon::parse($date[0])->toDateString();

        //Count Data
        $query = KpiResult::with('employee');
        $query->where('kpi_results.status', 0);
        // $query->whereBetween('date', [$from, $to]);

        $query->orderBy('kpi_results.created_at', 'desc');
        $recordsTotal = $query->count();

        //Select Pagination
        $query = KpiResult::with('employee')
        ->select('kpi_results.*')
        ->where('kpi_results.status', 0);
        // $query->whereBetween('date', [$from, $to]);
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
    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $employee_id    = strtoupper(str_replace("'", "''", $request->employee_id));
        $nid            = $request->nid;
        $department     = $request->department ? explode(',', $request->department) : null;
        $workgroup      = $request->workgroup ? explode(',', $request->workgroup) : null;
        $overtime       = $request->overtime;
        $month          = $request->month;
        $year           = $request->year;
        $from           = $request->from ? Carbon::parse($request->from)->startOfDay()->toDateTimeString() : null;
        $to             = $request->to ? Carbon::parse($request->to)->endOfDay()->toDateTimeString() : null;
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
        $query->select('kpi_results.*', 'employees.user_id', 'employees.name as employee', 'employees.nik as nik');
        $query->leftJoin('employees', 'employees.id', '=', 'kpi_results.employee_id');
        $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
        $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
        $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
        $query->where('employees.status', 1);
        $query->where('kpi_results.status', 0);
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
        $query->select('kpi_results.*', 'employees.user_id', 'employees.name as employee', 'employees.nik as nik');
        $query->leftJoin('employees', 'employees.id', '=', 'kpi_results.employee_id');
        $query->leftJoin('departments', 'departments.id', '=', 'employees.department_id');
        $query->leftJoin('titles', 'titles.id', '=', 'employees.title_id');
        $query->leftJoin('work_groups', 'work_groups.id', '=', 'employees.workgroup_id');
        $query->where('employees.status', 1);
        $query->where('kpi_results.status', 0);
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
     * @param  \App\KpiResult  $kpiResult
     * @return \Illuminate\Http\Response
     */
    public function show(KpiResult $kpiResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\KpiResult  $kpiResult
     * @return \Illuminate\Http\Response
     */
    public function edit(KpiResult $kpiResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\KpiResult  $kpiResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, KpiResult $kpiResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\KpiResult  $kpiResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(KpiResult $kpiResult)
    {
        //
    }
}
