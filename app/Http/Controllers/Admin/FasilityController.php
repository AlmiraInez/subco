<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fasility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class FasilityController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'fasility'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.fasility.index');
    }
    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $fasility = strtoupper($request->fasility);

        //Count Data
        $query = DB::table('fasilities');
        $query->select('fasilities.*');
        $query->whereRaw("upper(fasility) like '%$fasility%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('fasilities');
        $query->select('fasilities.*');
        $query->whereRaw("upper(fasility) like '%$fasility%'");

        $query->offset($start);
        $query->limit($length);
        $fasilities = $query->get();

        $data = [];
        foreach ($fasilities as $fasility) {
            $fasility->no = ++$start;
            $data[] = $fasility;
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
        // $code = strtoupper($request->code);
        $fasility = strtoupper($request->fasility);

        //Count Data
        $query = DB::table('fasilities');
        $query->select('fasilities.*');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(fasility) like '%$fasility%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('fasilities');
        $query->select('fasilities.*');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(fasility) like '%$fasility%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $fasilities = $query->get();

        $data = [];
        foreach ($fasilities as $fasility) {
            $fasility->no = ++$start;
            $data[] = $fasility;
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
        return view('admin.fasility.create');
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
            'fasility'        => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }
        $fasility = Fasility::create([
            'fasility'         => $request->fasility,
        ]);
        if (!$fasility) {
            return response()->json([
                'status' => false,
                'message'     => $fasility
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('fasility.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fasility  $fasility
     * @return \Illuminate\Http\Response
     */
    public function show(Fasility $fasility)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fasility  $fasility
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $fasility = Fasility::find($id);
        if ($fasility) {
            return view('admin.fasility.edit', compact('fasility'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Fasility  $fasility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'fasility'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $fasility = Fasility::find($id);
        $fasility->fasility  = $request->fasility;
        $fasility->save();
        if (!$fasility) {
            return response()->json([
                'status' => false,
                'message'   => $fasility
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Berhasil Merubah Data!",
            'results'   => route('fasility.index'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fasility  $fasility
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $fasility = Fasility::find($id);
            $fasility->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'     => false,
                'message'     => 'Error delete data'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete data'
        ], 200);
    }
}
