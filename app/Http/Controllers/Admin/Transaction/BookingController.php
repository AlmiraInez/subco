<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Models\Transaction;
use App\Models\Room;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class BookingController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/transaction/booking'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.transaction.booking.index');
    }
    public function indexapproval()
    {
        return view('admin.transaction.booking.indexapproval');
    }
    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $code = strtoupper($request->code);
        // $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('transactions');
        $query->select('transactions.*',
                     'tenants.name as tenant_name',
                     'tenants.company_name as company_name',
                     'room_categories.category as room_category');
        $query->leftJoin('tenants', 'tenants.id', '=', 'transactions.tenant_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'transactions.room_category');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(code) like '%$code%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('transactions');
        $query->select('transactions.*',
                    'tenants.name as tenant_name',
                    'tenants.company_name as company_name',
                    'room_categories.category as room_category');
        $query->leftJoin('tenants', 'tenants.id', '=', 'transactions.tenant_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'transactions.room_category');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(code) like '%$code%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $transactions = $query->get();

        $data = [];
        foreach ($transactions as $tenant) {
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
    public function readapproval(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $code = strtoupper($request->code);
        // $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('transactions');
        $query->select(
            'transactions.*',
            'tenants.name as tenant_name',
            'tenants.company_name as company_name',
            'room_categories.category as room_category'
        );
        $query->leftJoin('tenants', 'tenants.id', '=', 'transactions.tenant_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'transactions.room_category');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->where("status_approval", "=",Transaction::STAT_PROCCESSING);
        $query->whereRaw("upper(code) like '%$code%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('transactions');
        $query->select(
            'transactions.*',
            'tenants.name as tenant_name',
            'tenants.company_name as company_name',
            'room_categories.category as room_category'
        );
        $query->leftJoin('tenants', 'tenants.id', '=', 'transactions.tenant_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'transactions.room_category');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->where("status_approval", "=", Transaction::STAT_PROCCESSING);
        $query->whereRaw("upper(code) like '%$code%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $transactions = $query->get();

        $data = [];
        foreach ($transactions as $tenant) {
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.transaction.booking.create');
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
            'category_id'      => 'required',
            'room_id'          => 'required',
            'tenant_id'        => 'required',
            'price'            => 'required',
            'start_date'       => 'required',
            'end_date'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'    => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $transaction = Transaction::create([
            'transaction_date' => date('Y-m-d'),
            'room_category'    => $request->category_id,
            // 'code'             => $request->code,
            'room_id'          => $request->room_id,
            'tenant_id'        => $request->tenant_id,
            'price'            => $request->price,
            'code'             => '',
            'period_rent'      => $request->period_rent,
            'payment_period'   => $request->payment_period,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'notes'            => $request->notes,
            'status'           => Transaction::STAT_BOOKING,
            'stat_approval'    => Transaction::STAT_PROCCESSING,
            'site_id'          => Session::get('site_id'),

        ]);
        $room = Room::find($request->room_id);
        $room->status = 2;
        $room->save();
        // dd($transaction);
        // print_r($transaction); die;
        if ($request->code) {
            $transaction->code = $request->code;
            $transaction->save();
        } else {
            $transaction->code = $transaction->code_system;
            $transaction->save();
        }
        
        if (!$transaction) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => $transaction
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('admin.transaction.booking.index'),
        ], 200);
    }
    public function addBooking($id)
    {
        $room = Room::with(['category'])->findOrFail($id);
        if ($room) {
            return view('admin.booking.create', compact('room'));
        } else {
            abort(404);
        }
    }
    public function savebooking(Request $request, $id)
    {
        $room = Room::find($id);
        $validator = Validator::make($request->all(), [
            'tenant_id'        => 'required',
            'start_date'       => 'required',
            'end_date'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'    => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $transaction = Transaction::create([
            'transaction_date' => date('Y-m-d'),
            'room_category'    => $room->category_id,
            // 'code'             => $request->code,
            'room_id'          => $room->id,
            'tenant_id'        => $request->tenant_id,
            'price'            => $room->price,
            'code'             => '',
            'period_rent'      => $request->period_rent,
            'payment_period'   => $request->payment_period,
            'start_date'       => $request->start_date,
            'end_date'         => $request->end_date,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'notes'            => $request->notes,
            'status'           => Transaction::STAT_BOOKING,
            'stat_approval'    => Transaction::STAT_PROCCESSING,
            'site_id'          => Session::get('site_id'),

        ]);
        $room->status = 1;
        $room->save();
        // dd($transaction);
        // print_r($transaction); die;
        if ($request->code) {
            $transaction->code = $request->code;
            $transaction->save();
        } else {
            $transaction->code = $transaction->code_system;
            $transaction->save();
        }

        if (!$transaction) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => $transaction
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('admin.transaction.booking.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::with(['tenant', 'room', 'category'])->findOrFail($id);

        return view('admin.transaction.booking.detail', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
