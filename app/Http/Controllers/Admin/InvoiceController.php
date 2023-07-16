<?php

namespace App\Http\Controllers\Admin;

use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'invoice'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo("aaaa");
        return view('admin.invoice.index');
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
        $query = DB::table('invoices');
        $query->select(
            'invoices.*',
            'tenants.name as tenant_name',
            'tenants.company_name as company_name',
            'room_categories.category as room_category'
        );
        $query->leftJoin('tenants', 'tenants.id', '=', 'invoices.tenant_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'invoices.room_category');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(code) like '%$code%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('invoices');
        $query->select(
            'invoices.*',
            'tenants.name as tenant_name',
            'tenants.company_name as company_name',
            'room_categories.category as room_category'
        );
        $query->leftJoin('tenants', 'tenants.id', '=', 'invoices.tenant_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'invoices.room_category');
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
    public function addInvoice($id)
    {
        $transaction = Transaction::with(['tenant', 'room', 'category'])->findOrFail($id);
        if ($transaction) {
            return view('admin.invoice.addinvoice', compact('transaction'));
        } else {
            abort(404);
        }
    }
    public function saveinvoice(Request $request,$id)
    {
       
        $transaction = Transaction::findOrFail($id);
        DB::beginTransaction();
        if($transaction){
            $invoice = Invoice::create([
                'invoice_date'     => date('Y-m-d'),
                'room_category'    => $transaction->room_category,
                'trans_id'         => $transaction->id,
                'room_id'          => $transaction->room_id,
                'tenant_id'        => $transaction->tenant_id,
                'price'            => $transaction->price,
                'discount'         => $transaction->discount,
                'period_payment'   => $transaction->payment_period,
                'period_amount'    => $transaction->period_rent,
                'amount'           => $transaction->price * $transaction->payment_period,
                'code'             => '',
                'payment_status'   => Invoice::STAT_UNPAID,
                'notes'            => $request->notes,
                'stat_approval'    => $request->status,
                'site_id'          => Session::get('site_id'),
    
            ]);
            if ($request->code) {
                $invoice->code = $request->code;
                $invoice->save();
            } else {
                $invoice->code = $invoice->code_system;
                $invoice->save();
            }
            if (!$invoice) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message'     => $invoice
                ], 400);
            }
        }
        // print_r($transaction); die;
        DB::commit();
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('invoice.index'),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'room_id'          => 'required',
            'tenant_id'        => 'required',
            'price'            => 'required',          
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'    => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $invoice = Invoice::create([
            'code'             => '',
            'invoice_date'     => date('Y-m-d'),
            'room_category'    => $request->room_category,
            'trans_id'         => $request->trans_id,
            'room_id'          => $request->room_id,
            'tenant_id'        => $request->tenant_id,
            'price'            => $request->price,
            'discount'         => $request->discount,
            'discount'         => $request->price * $request->payment_period,
            'payment_status'   => Invoice::STAT_UNPAID,
            'notes'            => $request->notes,
            'stat_approval'    => $request->status,
            'site_id'          => Session::get('site_id'),
        ]);

        $invoice->code = $request->code ? $request->code:$invoice->code_system;
        $invoice->save();
       
        if (!$invoice) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => $invoice
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('admin.transaction.booking.index'),
        ], 200);
    }
    public function pdf($id)
    {
        $invoice = Invoice::with('tenant')->with('category')->with('room')->with('transaction')->find($id);
        return view('admin.invoice.print', compact('invoice'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Invoice::with(['tenant', 'room', 'category'])->findOrFail($id);

        return view('admin.invoice.detail', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
