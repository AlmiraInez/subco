<?php

namespace App\Http\Controllers\Admin;

use App\Models\Workingtime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkingtimeDetail;
use App\Models\WorkingtimeLine;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class WorkingTimeController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'workingtime'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $working_time_type = strtoupper($request->working_time_type);
        $description = strtoupper($request->name);

        //Count Data
        $query = DB::table('workingtimes');
        $query->select('workingtimes.*');
        if ($description) {
            $query->whereRaw("upper(description) like '%$description%'");
        }
        if ($working_time_type) {
            $query->whereRaw("upper(working_time_type) like '$working_time_type'");
        }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('workingtimes');
        $query->select('workingtimes.*');
        if ($description) {
            $query->whereRaw("upper(description) like '%$description%'");
        }
        if ($working_time_type) {
            $query->whereRaw("upper(working_time_type) like '$working_time_type'");
        }
        $query->offset($start);
        $query->limit($length);
        $workingtimes = $query->get();

        $data = [];
        foreach ($workingtimes as $workingtime) {
            $workingtime->no = ++$start;
            $data[] = $workingtime;
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
        $working_time_type = strtoupper($request->working_time_type);

        //Count Data
        $query = DB::table('workingtimes');
        $query->select('workingtimes.*');
        if ($working_time_type) {
            $query->whereRaw("upper(working_time_type) like '$working_time_type'");
        }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('workingtimes');
        $query->select('workingtimes.*');
        if ($working_time_type) {
            $query->whereRaw("upper(working_time_type) like '$working_time_type'");
        }
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $workingtimes = $query->get();

        $data = [];
        foreach ($workingtimes as $workingtime) {
            $workingtime->no = ++$start;
            $data[] = $workingtime;
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
    public function index()
    {
        return view('admin.workingtime.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.workingtime.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'working_time_type' => 'required',
            'description'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $workingtime = Workingtime::create([
            'working_time_type' => $request->working_time_type,
            'working_time_start' => $request->working_time_start,
            'description'       => $request->description,
            'notes'             => $request->notes
        ]);
        if($workingtime){
            $department = explode(',', $request->department);
            foreach ($department as $key => $value) {
                $workingtimeline[] = array(
                    'workingtime_id' => $workingtime->id,
                    'department_id' => $value,
                    'created_at'    => Carbon::now()->toDateTimeString(),
                    'updated_at'    => Carbon::now()->toDateTimeString(),
                );
            }
            $timeline = WorkingtimeLine::insert($workingtimeline);
            if (!$timeline) {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => $workingtime
                ], 400);
            }
        }
        if ($workingtime) {
            foreach ($request->start as $key => $value) {
                if ($request->start[$key] > $request->finish[$key]) {
                    $start = changeDateFormat('Y-m-d H:i:s', date('Y-m-d', time()) . ' ' . $request->start[$key]);
                    $finish = changeDateFormat('Y-m-d H:i:s', date('Y-m-d', strtotime('+1 day')) . ' ' . $request->finish[$key]);
                    $workhour = (new Carbon($start))->diff(new Carbon($finish))->format('%h');
                } else {
                    $workhour = (new Carbon($request->finish[$key]))->diff(new Carbon($request->start[$key]))->format('%h');
                }
                $detail = WorkingtimeDetail::create([
                    'workingtime_id'    => $workingtime->id,
                    'start'             => isset($request->save[$key]) ? $request->start[$key] : null,
                    'finish'            => isset($request->save[$key]) ? $request->finish[$key] : null,
                    'min_in'            => isset($request->save[$key]) ? $request->min_in[$key] : null,
                    'max_out'           => isset($request->save[$key]) ? $request->max_out[$key] : null,
                    'workhour'          => isset($request->save[$key]) ? $workhour : null,
                    'day'               => $request->day[$key],
                    'status'            => isset($request->save[$key]) ? 1 : 0,
                    'min_workhour'      => $request->min_wt[$key]
                ]);
                if (!$detail) {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => $detail
                    ], 400);
                }
            }
        } else {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $workingtime
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('workingtime.index'),
        ], 200);
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
        $workingtime = Workingtime::with(array('detail' => function ($query) {
            $query->orderBy('id', 'asc');
        }))->with("workingtimeline")->find($id);
        if ($workingtime) {
            $department = [];
            foreach ($workingtime->workingtimeline as $working) {
                $department[] = $working->department->name;
            }
            return view('admin.workingtime.edit', compact('workingtime', 'department'));
        } else {
            abort(404);
        }
    }
    public function multi(Request $request){
        $data = $request->workingtime_id;
        // dd($data);
        $workingtime = Workingtime::with("workingtimeline")->where('id', $data)->first();
        // dd($workingtime);
        
            $department = [];
            foreach ($workingtime->workingtimeline as $working) {
            //  $brek->workgroup_id = $brek->workgroup_id;
             $working->department_name = $working->department->name;
             $department[] = $working;
            }
        // dd($department);
        
        return response()->json([
            'status'     => true,
            'results'     => $department,
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'description'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $workingtime = Workingtime::find($id);
        $workingtime->working_time_type = $request->working_time_type;
        $workingtime->working_time_start = $request->working_time_start;
        $workingtime->description       = $request->description;
        $workingtime->notes             = $request->notes;
        $workingtime->save();
        if ($workingtime) {
            $delete = WorkingtimeDetail::where('workingtime_id', $id);
            $delete->delete();
            foreach ($request->start as $key => $value) {
                if ($request->start[$key] > $request->finish[$key]) {
                    $start = changeDateFormat('Y-m-d H:i:s', date('Y-m-d', time()) . ' ' . $request->start[$key]);
                    $new_finish = changeDateFormat('Y-m-d H:i:s', changeDateFormat('Y-m-d', $start) . ' ' . $request->finish[$key]);
                    $finish = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($new_finish)));
                    $workhour = (new Carbon($start))->diff(new Carbon($finish))->format('%h');
                } else {
                    $workhour = (new Carbon($request->finish[$key]))->diff(new Carbon($request->start[$key]))->format('%h');
                }
                $detail = WorkingtimeDetail::create([
                    'workingtime_id'    => $workingtime->id,
                    'start'             => isset($request->save[$key]) ? $request->start[$key] : null,
                    'finish'            => isset($request->save[$key]) ? $request->finish[$key] : null,
                    'min_in'            => isset($request->save[$key]) ? $request->min_in[$key] : null,
                    'max_out'           => isset($request->save[$key]) ? $request->max_out[$key] : null,
                    'workhour'          => isset($request->save[$key]) ? $workhour : null,
                    'day'               => $request->day[$key],
                    'status'            => isset($request->save[$key]) > 0 ? 1 : 0,
                    'min_workhour'      => $request->min_wt[$key]
                ]);
                if (!$detail) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message'   => $detail
                    ], 400);
                }
            }
        } else {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'   => $workingtime
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('workingtime.index'),
        ], 200);
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
            $workingtime = Workingtime::find($id);
            $workingtime->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'     => false,
                'message'     => 'Data has been used to another page'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete data'
        ], 200);
    }
}