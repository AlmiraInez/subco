<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;


class TenantController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'tenant'));
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.tenant.index');
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
        $company = strtoupper($request->company);


        //Count Data
        $query = DB::table('tenants');
        $query->select('tenants.*');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(name) like '%$name%'");
        $query->whereRaw("upper(company_name) like '%$company%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('tenants');
        $query->select('tenants.*');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(name) like '%$name%'");
        $query->whereRaw("upper(company_name) like '%$company%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $tenants = $query->get();

        $data = [];
        foreach ($tenants as $tenant) {
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
        $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('tenants');
        $query->select('tenants.*');
        $query->whereRaw("upper(name) like '%$name%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('tenants');
        $query->select('tenants.*');
        $query->whereRaw("upper(name) like '%$name%'");

        $query->offset($start);
        $query->limit($length);
        $tenants = $query->get();

        $data = [];
        foreach ($tenants as $tenant) {
            $tenant->no = ++$start;
            $data[] = $tenant;
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
        return view('admin.tenant.create');
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
            'phone'        => 'required',
            'birth_date'   => 'required',
            'gender'       => 'required',
            'email'        => 'required',
            'title'        => 'required',
            'company_name' => 'required',
            'address'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }
        $tenant = Tenant::create([
            'name'         => $request->name,
            'birth_date'   => $request->birth_date,
            'phone'        => $request->phone,
            'gender'       => $request->gender,
            'email'        => $request->email,
            'title'        => $request->title,
            'company_name' => $request->company_name,
            'address'      => $request->address,
        ]);
        if (!$tenant) {
            return response()->json([
                'status' => false,
                'message'     => $tenant
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'results'     => route('tenant.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function show(Tenant $tenant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tenant = Tenant::find($id);
        if ($tenant) {
            return view('admin.tenant.edit', compact('tenant'));
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required',
            'phone'        => 'required',
            'birth_date'   => 'required',
            'gender'       => 'required',
            'email'        => 'required',
            'title'        => 'required',
            'company_name' => 'required',
            'address'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $tenant = Tenant::find($id);
        $tenant->name         = $request->name;
        $tenant->birth_date   = $request->birth_date;
        $tenant->phone        = $request->phone;
        $tenant->gender       = $request->gender;
        $tenant->email        = $request->email;
        $tenant->title        = $request->title;
        $tenant->company_name = $request->company_name;
        $tenant->address      = $request->address;
        $tenant->save();
        if (!$tenant) {
            return response()->json([
                'status' => false,
                'message'   => $tenant
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Berhasil Merubah Data!",
            'results'   => route('tenant.index'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tenant  $tenant
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $tenant = Tenant::find($id);
            $tenant->delete();
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
