<?php

namespace App\Http\Controllers\Admin;

use App\Models\RoomCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class RoomCategoryController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'roomcategory'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.roomcategory.index');
    }
    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $category = strtoupper($request->category);

        //Count Data
        $query = DB::table('room_categories');
        $query->select('room_categories.*');
        $query->whereRaw("upper(category) like '%$category%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('room_categories');
        $query->select('room_categories.*');
        $query->whereRaw("upper(category) like '%$category%'");
    
        $query->offset($start);
        $query->limit($length);
        $room_categories = $query->get();

        $data = [];
        foreach ($room_categories as $room_category) {
            $room_category->no = ++$start;
            $data[] = $room_category;
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
        $category = strtoupper($request->category);

        //Count Data
        $query = DB::table('room_categories');
        $query->select('room_categories.*');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(category) like '%$category%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('room_categories');
        $query->select('room_categories.*');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(category) like '%$category%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $room_categories = $query->get();

        $data = [];
        foreach ($room_categories as $category) {
            $category->no = ++$start;
            $data[] = $category;
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
        return view('admin.roomcategory.create');
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
            'category'        => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }
        $room_category = RoomCategory::create([
            'category'         => $request->category,
        ]);
        if (!$room_category) {
            return response()->json([
                'status' => false,
                'message'     => $room_category
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('roomcategory.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RoomCategory  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function show(RoomCategory $roomCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RoomCategory  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room_category = RoomCategory::find($id);
        if ($room_category) {
            return view('admin.roomcategory.edit', compact('room_category'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RoomCategory  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category'        => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $room_category = RoomCategory::find($id);
        $room_category->category  = $request->category;
        $room_category->save();
        if (!$room_category) {
            return response()->json([
                'status' => false,
                'message'   => $room_category
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Berhasil Merubah Data!",
            'results'   => route('roomcategory.index'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RoomCategory  $roomCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $room_category = RoomCategory::find($id);
            $room_category->delete();
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
