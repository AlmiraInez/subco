<?php

namespace App\Http\Controllers\Admin\Attendance;

use App\Http\Controllers\Controller;
use App\Libraries\Traits\InteractWithQrCodeGenerator;
use App\Models\AttendanceCode;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    use InteractWithQrCodeGenerator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        view()->share('menu_active', url('admin/attendance/barcode'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $qrCode = AttendanceCode::active()->inRandomOrder()->first();

        $data['qrCode'] = $this->qrCode(optional($qrCode)->code ?: 'Harap tunggu');
        $data['code'] = $this->getCode('Harap tunggu');

        return view('admin.attendance.barcode.index', $data);
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
        $qrCode = AttendanceCode::active()->inRandomOrder()->first();

        if (!$qrCode) {
            $data['qrCode'] = '';
            $data['code'] = 'Qr code telah habis di gunakan, silahkan generate ulang';
        }

        $data['qrCode'] = $this->qrCode(optional($qrCode)->code ?: 'qr code habis');
        $data['code'] = $this->getCode(optional($qrCode)->code ?: 'qr code habis');

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
