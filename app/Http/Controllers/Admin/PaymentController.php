<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'payment'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.payment.index');
    }
    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $code = strtoupper($request->code);
        $company = strtoupper($request->company);
        $tenant_id = $request->tenant_id;
        $status = $request->status;
        $status_approval = $request->status_approval;
        $transaction_date = $request->transaction_date ? Carbon::parse($request->transaction_date)->startOfDay()->toDateTimeString() : null;
        $id = auth()->user()->id;
        $tenant_id = auth()->user()->employee_id;
        $role = DB::table('role_user')->select('role_id')->where('user_id', $id)->first();

        //Count Data
        $query = DB::table('payments');
        $query->select(
            'payments.*',
            'invoices.code ad inv_code',
            'invoices.invoice_ddate ad inv_date',
            'tenants.name as tenant_name',
            'rooms.name as room_name',
            'tenants.company_name as company_name',
            'room_categories.category as room_category'
        );
        $query->leftJoin('tenants', 'tenants.id', '=', 'payments.tenant_id');
        $query->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id');
        $query->leftJoin('rooms', 'rooms.id', '=', 'payments.room_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'payments.room_category');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(payments.code) like '%$code%'");
        $query->whereRaw("upper(tenants.company_name) like '%$company%'");
        if ($transaction_date) {
            $query->where("payment_date", $transaction_date);
        }
        if ($tenant_id) {
            $query->where("payments.tenant_id", $tenant_id);
        }
        if ($status_approval) {
            $query->where("payments.stat_approval", $status_approval);
        }
        if ($role->role_id != 1) {
            $query->where("payments.tenant_id", $tenant_id);
        }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('payments');
        $query->select(
            'payments.*',
            'tenants.name as tenant_name',
            'tenants.company_name as company_name',
            'room_categories.category as room_category',
            'invoices.code as inv_code',
            'invoices.invoice_date as inv_date',
            'rooms.name as room_name',
            'tenants.name as tenant_name',
        );
        $query->leftJoin('tenants', 'tenants.id', '=', 'payments.tenant_id');
        $query->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id');
        $query->leftJoin('rooms', 'rooms.id', '=', 'payments.room_id');
        $query->leftJoin('room_categories', 'room_categories.id', '=', 'payments.room_category');
        // $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(payments.code) like '%$code%'");
        $query->whereRaw("upper(tenants.company_name) like '%$company%'");
        if ($transaction_date) {
            $query->where("payment_date", $transaction_date);
        }
        if ($tenant_id) {
            $query->where("payments.tenant_id", $tenant_id);
        }
        if ($status_approval) {
            $query->where("payments.stat_approval", $status_approval);
        }
        if ($role->role_id != 1) {
            $query->where("payments.tenant_id", $tenant_id);
        }
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $payments = $query->get();

        $data = [];
        foreach ($payments as $tenant) {
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
    public function addPayment($id)
    {
        $transaction = Invoice::with(['transaction','tenant', 'room', 'category'])->findOrFail($id);
        if ($transaction) {
            return view('admin.payment.addpayment', compact('transaction'));
        } else {
            abort(404);
        }
    }
    public function savepayment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        DB::beginTransaction();
        if ($invoice) {
            $payment = Payment::create([
                'payment_date'     => date('Y-m-d'),
                'room_category'    => $invoice->room_category,
                'trans_id'         => $invoice->trans_id,
                'room_id'          => $invoice->room_id,
                'tenant_id'        => $invoice->tenant_id,
                'invoice_id'       => $invoice->id,
                'payment_img'      => '',
                // 'price'            => $invoice->price,
                'discount'         => $invoice->discount,
                // 'period_payment'   => $invoice->payment_period,
                // 'period_amount'    => $invoice->period_rent,
                'invoice_amount'   => $invoice->amount,
                'payment_amount'   => $invoice->amount,
                'code'             => '',
                'stat_approval'    => Payment::STAT_PROCCESSING,
                'notes'            => $request->notes,
                'site_id'          => Session::get('site_id'),

            ]);
           
            if ($request->hasfile('image1')) {
                $payment_img1 = $request->file('image1');
                if (file_exists($payment->payment_img)) {
                    unlink($payment->payment_img);
                }
                $rd = Str::random(5);
                $img1_name = 'img.' . $rd . '.' . $payment_img1->getClientOriginalExtension();
                $src = 'assets/payments/';
                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $payment_img1->move($src, $img1_name);
                $payment->payment_img = $src.$img1_name;
                $payment->save();
            }
            
            if ($request->code) {
                $payment->code = $request->code;
                $payment->save();
            } else {
                $payment->code = $payment->code_system;
                $payment->save();
            }
            
            if (!$payment) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message'     => $payment
                ], 400);
            }
        }

        // var_dump($payment);
        // print_r($transaction); die;
        DB::commit();
        return response()->json([
            'status'     => true,
            'message'    => "Berhasil menyimpan data",
            'results'    => route('payment.index'),
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Payment::with(['invoice','tenant', 'room', 'category','transaction'])->findOrFail($id);
        $status = Transaction::findOrFail($transaction->trans_id);
        $id = auth()->user()->id;
        $role = DB::table('role_user')->select('role_id')->where('user_id', $id)->first();
        return view('admin.payment.detail', compact('transaction', 'role', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
