<?php

namespace App\Http\Controllers\Admin;

use App\Models\Room;
use App\Models\RoomFasility;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class RoomController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'room'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.room.index');
    }
    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        // $code = strtoupper($request->code);
        $name = strtoupper($request->name);
        $location = strtoupper($request->location);


        //Count Data
        $query = DB::table('rooms');
        $query->select('rooms.*','room_categories.category as room_category');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'rooms.category_id');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(name) like '%$name%'");
        $query->whereRaw("upper(location) like '%$location%'");

        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('rooms');
        $query->select('rooms.*','room_categories.category as room_category');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'rooms.category_id');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(name) like '%$name%'");
        $query->whereRaw("upper(location) like '%$location%'");

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $rooms = $query->get();

        $data = [];
        foreach ($rooms as $tenant) {
            $tenant->no = ++$start;
            $data[] = $tenant;
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
        $category_id = $request->category_id;
        $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('rooms');
        $query->select('rooms.*');
        $query->whereRaw("upper(name) like '%$name%'");
        if ($category_id) {
            $query->where('category_id', '=', $category_id);
        }
        $query->where('status', '=', $category_id);
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('rooms');
        $query->select('rooms.*');
        $query->whereRaw("upper(name) like '%$name%'");
        if ($category_id) {
            $query->where('category_id', '=', $category_id);
        }
        $query->where('status', '=', $category_id);
        $query->offset($start);
        $query->limit($length);
        $rooms = $query->get();

        $data = [];
        foreach ($rooms as $room) {
            $room->no = ++$start;
            $data[] = $room;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function multi(Request $request)
    {
        $data = $request->room_id;
        // dd($data);
        $room = Room::with("roomfasilities")->where('id', $data)->first();

        $fasilities = [];
        foreach ($room->roomfasilities as $v) {
            //  $v->workgroup_id = $v->workgroup_id;
            $v->fasility_name = $v->fasility->fasility;
            $fasilities[] = $v;
        }
        // dd($workgroup);

        return response()->json([
            'status'     => true,
            'results'     => $fasilities,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.room.create');
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
            'name'        => 'required',
            'location'    => 'required',
            'width'       => 'required',
            'address'     => 'required',
            'category_id' => 'required',
            'status'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $room = Room::create([
            'name'        => $request->name,
            'location'    => $request->location,
            'width'       => $request->width,
            'price'       => $request->price,
            'category_id' => $request->category_id,
            'note'        => $request->notes,
            'address'     => $request->address,
            'status'      => $request->status,
            'site_id'     => Session::get('site_id'),
        ]);
        if ($room) {
            $fasility = explode(',', $request->fasility);
            foreach ($fasility as $key => $value) {
                $fasilities[] = array(
                    'room_id'  => $room->id,
                    'fasility_id'  => $value,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                );
            }
            $fas = RoomFasility::insert($fasilities);
            if (!$fas) {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => $fas
                ], 400);
            }
        }
        if ($request->hasfile('video')) {
            $room_video = $request->file('video');
            if (file_exists($room->video)) {
                unlink($room->video);
            }
            $rd = Str::random(5);
            $video_name = 'video.'. $rd.'.' . $request->video->getClientOriginalExtension();
            $src = 'assets/rooms/video/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_video->move($src, $video_name);
            $room->video = $video_name;
            $room->save();
        }
        if ($request->hasfile('image1')) {
            $room_img1 = $request->file('image1');
            if (file_exists($room->img1)) {
                unlink($room->img1);
            }
            $rd = Str::random(5);
            $img1_name = 'img.' . $rd . '.' . $room_img1->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img1->move($src, $img1_name);
            $room->img1 = $img1_name;
            $room->save();
        }
        if ($request->hasfile('image2')) {
            $room_img2 = $request->file('image2');
            if (file_exists($room->img2)) {
                unlink($room->img2);
            }
            $rd = Str::random(5);
            $img2_name = 'img.' . $rd . '.' . $room_img2->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img2->move($src, $img2_name);
            $room->img2 = $img2_name;
            $room->save();
        }
        if ($request->hasfile('image3')) {
            $room_img3 = $request->file('image3');
            if (file_exists($room->img3)) {
                unlink($room->img3);
            }
            $rd = Str::random(5);
            $img3_name = 'img.' . $rd . '.' . $room_img3->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img3->move($src, $img3_name);
            $room->img3 = $img3_name;
            $room->save();
        }
        if ($request->hasfile('image4')) {
            $room_img4 = $request->file('image4');
            if (file_exists($room->img4)) {
                unlink($room->img4);
            }
            $rd = Str::random(5);
            $img4_name = 'img.' . $rd . '.' . $room_img4->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img4->move($src, $img4_name);
            $room->img4 = $img4_name;
            $room->save();
        }
        if ($request->hasfile('image5')) {
            $room_img5 = $request->file('image5');
            if (file_exists($room->img5)) {
                unlink($room->img5);
            }
            $rd = Str::random(5);
            $img5_name = 'img.' . $rd . '.' . $room_img5->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img5->move($src, $img5_name);
            $room->img5 = $img5_name;
            $room->save();
        }
        if ($request->hasfile('image6')) {
            $room_img6 = $request->file('image6');
            if (file_exists($room->img6)) {
                unlink($room->img6);
            }
            $rd = Str::random(5);
            $img6_name = 'img.' . $rd . '.' . $room_img6->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img6->move($src, $img6_name);
            $room->img6 = $img6_name;
            $room->save();
        }
       
        if (!$room) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'     => $room
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'     => true,
            'results'     => route('room.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::with(['category', 'fasilities'])->findOrFail($id);
        // $fasilities = RoomFasility::with(['fasilties'])->where('room_id', $id)->get();

        return view('admin.room.view', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $room = Room::with(['category', 'fasilities'])->findOrFail($id);
        // $fasilities = RoomFasility::with(['fasilties'])->where('room_id', $id)->get();

        return view('admin.room.edit', compact('room'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'location'    => 'required',
            'width'       => 'required',
            'address'     => 'required',
            'category_id' => 'required',
            'status'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $room = Room::find($id);
        $room->name        = $request->name;
        $room->location    = $request->location;
        $room->width       = $request->width;
        $room->price       = $request->price;
        $room->category_id = $request->category_id;
        $room->note        = $request->notes;
        $room->address     = $request->address;
        $room->status      = $request->status;
        $room->site_id     = Session::get('site_id');
        $room->save();
        
        if ($room) {
            RoomFasility::truncate();
            $fasility = explode(',', $request->fasility);
            foreach ($fasility as $key => $value) {
                $fasilities[] = array(
                    'room_id'  => $room->id,
                    'fasility_id'  => $value,
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                );
            }
            $fas = RoomFasility::insert($fasilities);
            if (!$fas) {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => $fas
                ], 400);
            }
        }
        if ($request->hasfile('video')) {
            $room_video = $request->file('video');
            if (file_exists($room->video)) {
                unlink($room->video);
            }
            $rd = Str::random(5);
            $video_name = 'video.' . $rd . '.' . $request->video->getClientOriginalExtension();
            $src = 'assets/rooms/video/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_video->move($src, $video_name);
            $room->video = $video_name;
            $room->save();
        }
        if ($request->hasfile('image1')) {
            $room_img1 = $request->file('image1');
            if (file_exists($room->img1)) {
                unlink($room->img1);
            }
            $rd = Str::random(5);
            $img1_name = 'img.' . $rd . '.' . $room_img1->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img1->move($src, $img1_name);
            $room->img1 = $img1_name;
            $room->save();
        }
        if ($request->hasfile('image2')) {
            $room_img2 = $request->file('image2');
            if (file_exists($room->img2)) {
                unlink($room->img2);
            }
            $rd = Str::random(5);
            $img2_name = 'img.' . $rd . '.' . $room_img2->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img2->move($src, $img2_name);
            $room->img2 = $img2_name;
            $room->save();
        }
        if ($request->hasfile('image3')) {
            $room_img3 = $request->file('image3');
            if (file_exists($room->img3)) {
                unlink($room->img3);
            }
            $rd = Str::random(5);
            $img3_name = 'img.' . $rd . '.' . $room_img3->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img3->move($src, $img3_name);
            $room->img3 = $img3_name;
            $room->save();
        }
        if ($request->hasfile('image4')) {
            $room_img4 = $request->file('image4');
            if (file_exists($room->img4)) {
                unlink($room->img4);
            }
            $rd = Str::random(5);
            $img4_name = 'img.' . $rd . '.' . $room_img4->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img4->move($src, $img4_name);
            $room->img4 = $img4_name;
            $room->save();
        }
        if ($request->hasfile('image5')) {
            $room_img5 = $request->file('image5');
            if (file_exists($room->img5)) {
                unlink($room->img5);
            }
            $rd = Str::random(5);
            $img5_name = 'img.' . $rd . '.' . $room_img5->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img5->move($src, $img5_name);
            $room->img5 = $img5_name;
            $room->save();
        }
        if ($request->hasfile('image6')) {
            $room_img6 = $request->file('image6');
            if (file_exists($room->img6)) {
                unlink($room->img6);
            }
            $rd = Str::random(5);
            $img6_name = 'img.' . $rd . '.' . $room_img6->getClientOriginalExtension();
            $src = 'assets/rooms/img/';
            if (!file_exists($src)) {
                mkdir($src, 0777, true);
            }
            $room_img6->move($src, $img6_name);
            $room->img6 = $img6_name;
            $room->save();
        }

        if (!$room) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'     => $room
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'     => true,
            'results'     => route('room.index'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
        //
    }
}
