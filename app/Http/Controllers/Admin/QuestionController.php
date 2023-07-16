<?php

namespace App\Http\Controllers\Admin;

use App\Models\Question;
use App\Models\Department;
use App\Models\QuestionDepartment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class QuestionController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/question'));
        // $this->middleware('accessmenu', ['except' => 'select']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.question.index');
    }
    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        // $arsip = $request->category;

        //Count Data
        $query = Question::whereRaw("coalesce(upper(description),'') like '%$name%'");
        // if ($arsip) {
        //     $query->onlyTrashed();
        // }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = Question::whereRaw("coalesce(upper(description),'') like '%$name%'");
        // if ($arsip) {
        //     $query->onlyTrashed();
        // }
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

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = Question::whereRaw("upper(type) like '%$name%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = Question::whereRaw("upper(type) like '%$name%'");
        $query->orderBy('order', 'asc');
        $query->offset($start);
        $query->limit($length);
        $results = $query->get();

        $data = [];
        foreach ($results as $result) {
            $result->no = ++$start;
            $data[] = $result;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::orderBy('departments.id', 'asc')->get();
        return view('admin.question.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'order'                     => 'required|unique:questions',
            'type'                      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $question = Question::create([
            'order'                     => $request->order,
            'is_parent'                 => 0,
            'question_parent_code'      => null,
            'answer_parent_code'        => null,
            'type'                      => $request->type,
            'answer_type'               => 'radio',
            'description'               => $request->description,
            'description_information'   => $request->description,
            'frequency'                 =>'harian',
            'start_date'                => null,
            'finish_date'               => null,
        ]);

        if (!$question) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => $question
            ], 400);
        }

        if($request->department)
        {
            foreach ($request->department as $key => $value)
            {
                if (isset($request->department_status[$value])) {
                    $questiondepartment = QuestionDepartment::create([
                        'question_id' => $question->id,
                        'department_id' => $value
                    ]);
                    if (!$questiondepartment) {
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'     => $questiondepartment
                        ], 400);
                    }
                }
            }
        }

        DB::commit();
        return response()->json([
            'status'     => true,
            'results'    => route('question.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::find($id);
        if ($question) {
            return view('admin.question.detail', compact('question'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
        $departments = Department::select('departments.*', 'question_departments.id as question_department_id')
        ->leftJoin('question_departments', function ($join) use ($id) {
            $join->on('question_departments.department_id', '=', 'departments.id')
            ->where('question_id', '=', $id);
        })
        ->orderBy('departments.id', 'asc')->get();

        if ($question) {
            return view('admin.question.edit', compact('question', 'departments'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'order'                     => 'required',
            'type'                      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $question = Question::find($id);
        $question->order                    = $request->order;
        $question->type                     = $request->type;
        $question->description              = $request->description;
        $question->description_information  = $request->description;
        $question->save();

        if (!$question) {
            return response()->json([
                'status'    => false,
                'message'   => $question
            ], 400);
        }

        if ($request->department) {
            $exception = [];
            foreach ($request->department as $key => $value) {
                if (isset($request->department_status[$value])) {
                    array_push($exception, $value);
                    $check = QuestionDepartment::where('question_id', $question->id)->where('department_id', $value)->first();
                    if (!$check) {
                        $questiondepartment = QuestionDepartment::create([
                            'question_id' => $question->id,
                            'department_id' => $value
                        ]);
                        if (!$questiondepartment) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'message'     => $questiondepartment
                            ], 400);
                        }
                    }
                }
            }
            $questiondepartment = QuestionDepartment::whereNotIn('department_id', $exception)->where('question_id', $question->id)->delete();
        }

        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('question.index'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $question = Question::find($id);
            $question->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'     => false,
                'message'    => 'Error delete data'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete data'
        ], 200);
    }
}
