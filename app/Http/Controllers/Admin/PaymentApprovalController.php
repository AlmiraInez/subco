<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


class PaymentApprovalController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/paymentapproval'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.paymentapproval.index');
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
        $transaction_date = $request->transaction_date ? Carbon::parse($request->transaction_date)->startOfDay()->toDateTimeString() : null;
        // $name = strtoupper($request->name);
        // $name = strtoupper($request->name);

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
        $query->where("payments.stat_approval", "=", Payment::STAT_PROCCESSING);
        $query->whereRaw("upper(payments.code) like '%$code%'");
        $query->whereRaw("upper(tenants.company_name) like '%$company%'");
        if ($transaction_date) {
            $query->where("payment_date", $transaction_date);
        }
        if ($tenant_id) {
            $query->where("payment.tenant_id", $tenant_id);
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
        $query->where("payments.stat_approval", "=", Payment::STAT_PROCCESSING);
        $query->whereRaw("upper(payments.code) like '%$code%'");
        $query->whereRaw("upper(tenants.company_name) like '%$company%'");
        if ($transaction_date) {
            $query->where("payment_date", $transaction_date);
        }
        if ($tenant_id) {
            $query->where("payment.tenant_id", $tenant_id);
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
    public function editapproval($id)
    {
        $transaction = Payment::with(['tenant', 'room', 'category','transaction'])->findOrFail($id);
        if ($transaction) {
            return view('admin.paymentapproval.editapproval', compact('transaction'));
        } else {
            abort(404);
        }
    }
    public function updateapprove(Request $request, $id)
    {
        $transaction = Payment::find($id);
        $transaction->stat_approval = $request->status;
        $transaction->approval_date = date('Y-m-d');
        $transaction->save();
        if ($transaction->stat_approval == Payment::STAT_APPROVED) {
            $inv = Invoice::find($transaction->invoice_id);
            $inv->payment_status = Invoice::STAT_PAID ;
            $inv->payment_date = date('Y-m-d');
            $inv->save();
        }
        if (!$transaction) {
            return response()->json([
                'status'    => false,
                'message'   => $transaction
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'results'   => route('paymentapproval.index'),
            'message'   => 'Berhasil merubah data!',
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $transaction = Transaction::with(['tenant', 'room', 'category'])->findOrFail($id);

        // return view('admin.Payment.booking.detail', compact('Payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $Payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $Payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $Payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $Payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $Payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $Payment)
    {
        //
    }
}
