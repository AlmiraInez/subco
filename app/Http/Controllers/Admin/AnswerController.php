<?php

namespace App\Http\Controllers\Admin;

use App\Models\Answer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.answer.index');
    }
    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $question_id = $request->question_id;
        // $arsip = $request->archive;

        //Count Data
        $query = Answer::where('question_id', $question_id);
        // if ($arsip) {
        //     $query->onlyTrashed();
        // }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = Answer::where('question_id', $question_id);
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
        $question = $request->question_id;

        //Count Data
        $query = Answer::whereRaw("upper(description) like '%$name%'");
        // if ($question) {
        //     $query->where('assessment_question_id', $question);
        // }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = Answer::with('question')->whereRaw("upper(description) like '%$name%'");
        // if ($question) {
        //     $query->where('assessment_question_id', $question);
        // }
        $query->offset($start * $length);
        $query->limit($length);
        $query->orderBy('question_id', 'asc');
        $results = $query->get();

        $data = [];
        foreach ($results as $result) {
            $result->no = ++$start;
            if ($result->question) {
                $data[] = $result;
            }
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
        $validator = Validator::make($request->all(), [
            'question_id'               => 'required',
            'description'               => 'required',
            'rating'                    => 'required',
            'order'                     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $existOrder = Answer::where('question_id', $request->question_id)->where('order', $request->order)->first();
        if ($existOrder) {
            return response()->json([
                'status'    => false,
                'message'   => 'Answer already been used'
            ], 400);
        }


        $answer = Answer::create([
            'question_id'    => $request->question_id,
            'order'          => $request->order,
            'answer_type'    => '',
            'description'    => $request->description,
            'rating'         => $request->rating,
            'information'    => $request->information,
        ]);

        if (!$answer) {
            return response()->json([
                'status'      => false,
                'message'     => $answer
            ], 400);
        }
        
        return response()->json([
            'status'     => true,
            'message'    => 'Success Create Data'
        ], 200);
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show(Answer $answer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $answer = Answer::find($id);
        return response()->json([
            'status'    => true,
            'data'      => $answer
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question_id'    => 'required',
            'description'    => 'required',
            'rating'         => 'required',
            'order'          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $existOrder = Answer::where('question_id', $request->question_id)->where('order', $request->order)->where('id', '<>', $id)->first();
        if ($existOrder) {
            return response()->json([
                'status'    => false,
                'message'   => 'Answer already been used'
            ], 400);
        }

        $answer = Answer::find($id);
        $answer->order                  = $request->order;
        $answer->question_id            = $request->question_id;
        $answer->description            = $request->description;
        $answer->rating                 = $request->rating;
        $answer->information            = $request->information;
        $answer->save();

        if (!$answer) {
            return response()->json([
                'status'    => false,
                'message'     => $answer
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success Update Data'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $answer = Answer::find($id);
            $answer->delete();
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
