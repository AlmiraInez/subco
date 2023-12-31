<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fasility;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use PDF;

class CheckoutController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'checkout'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.checkout.index');
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
        $code = strtoupper($request->code);
        $code = strtoupper($request->code);
        $company = strtoupper($request->company);
        $tenant_id = $request->tenant_id;
        $transaction_date = $request->transaction_date ? Carbon::parse($request->transaction_date)->startOfDay()->toDateTimeString() : null;

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
        $query->where("status", "=", Transaction::STAT_CHECKOUT);
        $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(tenants.company_name) like '%$company%'");
        if ($transaction_date) {
            $query->where("transaction_date", $transaction_date);
        }
        if ($tenant_id) {
            $query->where("transactions.tenant_id", $tenant_id);
        }
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
        $query->where("status", "=", Transaction::STAT_CHECKOUT);
        $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(tenants.company_name) like '%$company%'");
        if ($transaction_date) {
            $query->where("transaction_date", $transaction_date);
        }
        if ($tenant_id) {
            $query->where("transactions.tenant_id", $tenant_id);
        }
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
    public function addCheckout($id)
    {
        // echo("aaaa");
        $transaction = Transaction::with(['tenant', 'room', 'category'])->findOrFail($id);
        // dd($transaction);
        if ($transaction) {
            return view('admin.checkout.addcheckout', compact('transaction'));
        } else {
            abort(404);
        }
    }
    public function savecheckout(Request $request, $id)
    {

        $transaction = Transaction::findOrFail($id);
        DB::beginTransaction();
        if ($transaction) {
            if ($request->hasfile('image1')) {
                $payment_img1 = $request->file('image1');
                if (file_exists($transaction->doc2)) {
                    unlink($transaction->doc2);
                }
                $rd = Str::random(5);
                $img1_name = 'doc.' . $rd . '.' . $payment_img1->getClientOriginalExtension();
                $src = 'assets/checkout/';
                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $payment_img1->move($src, $img1_name);
                $transaction->doc2 = $src . $img1_name;
                $transaction->note_checkout = $request->notes;
                $transaction->checkout_date = date('Y-m-d');
                $transaction->status = Transaction::STAT_CHECKOUT;
                $transaction->save();

                $room = Room::findOrFail($transaction->room_id);
                $room->status = 1;
                $room->save();
            }
        }
        // print_r($transaction); die;
        DB::commit();
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('checkout.index'),
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
    public function getpdf($id)
    {
        // $data = ['title' => 'Laravel 5.8 HTML to PDF'];
        // $pdf = PDF::loadView('htmlPDF', $data);
        // return $pdf->download('demonutslaravel.pdf');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Fasility  $fasility
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::with(['tenant', 'room', 'category'])->findOrFail($id);

        return view('admin.checkout.detail', compact('transaction'));
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
