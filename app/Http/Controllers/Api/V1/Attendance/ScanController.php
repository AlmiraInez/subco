<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Exceptions\ScanBarcodeException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\ScanInRequest;
use App\Http\Resources\Attendance\LogResource;
use App\Libraries\Traits\InteractWithApiResponse;
use App\Models\Attendance;
use App\Models\AttendanceCode;
use App\Models\AttendanceLog;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ScanController extends Controller
{
    use InteractWithApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScanInRequest $request)
    {

        return DB::transaction(function() use($request) {

            $getCode = AttendanceCode::where('code', "$request->barcode")->where('status', 'not scanned')->first();

            if (!$getCode) {
                throw new ScanBarcodeException("Invalid Barcode");
            }

            // return $this->success($getCode);
            if ($getCode->update(['status' => 'scanned'])) {

                $attendance = Attendance::where([
                    'employee_id' => $request->user('api')->employee_id,
                    'attended_at' => date('Y-m-d')
                ])->first();

                if (!$attendance) {
                    $createAttendance = Attendance::create([
                        'employee_id' => $request->user('api')->employee_id,
                        'attended_at' => now(),
                        'in_at' => now(),
                        'attended_at' => date('Y-m-d'),
                    ]);
                    if ($createAttendance) {

                        $checkLogs = AttendanceLog::where([
                            'employee_id' => $request->user('api')->employee_id,
                            'attended_at' => date('Y-m-d'),
                            'type' => 'in'
                        ])->first();

                        if ($checkLogs) {
                            throw new ScanBarcodeException("Sudah melakukan absen masuk!");
                        }

                        $recordLogs = AttendanceLog::create([
                            'attendance_id' => $createAttendance->id,
                            'employee_id' => $request->user('api')->employee_id,
                            'type' => 'in',
                            'attended_at' => now()
                        ]);

                        if ($recordLogs) {
                            return $this->success(Response::HTTP_OK, "Absen Masuk Berhasil!", [
                                'day' => date('l, d-m-Y', strtotime($recordLogs->attended_at)),
                                'time' => date('H:i:s', strtotime($recordLogs->attended_at)),
                                'type' => optional($recordLogs->employee)->working_time_type
                            ]);
                        }
                    }
                }
                return $this->error("Sudah melakukan absen masuk!");
            }
        });
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
    public function destroy(Request $request)
    {

        return DB::transaction(function() use($request) {

            $getAttendance = Attendance::where([
                'employee_id' => $request->user('api')->employee_id,
                'attended_at' => date('Y-m-d')
            ])->first();


            $getLogAttendanceIn = AttendanceLog::where([
                'attendance_id' => $getAttendance->id,
                'employee_id' => $request->user('api')->employee_id,
                // 'attended_at' => date('Y-m-d'),
                'type' => 'in'
            ])->first();

            if ($getAttendance && !$getLogAttendanceIn) {
                throw new ScanBarcodeException("Anda belum absen sama sekali!");
            }

            // dd($getLogAttendanceIn);

            if ($getAttendance->update(['out_at' => now()])) {            
                $recordLogOut = Attendancelog::where([
                    'attendance_id' => $getAttendance->id,
                    'employee_id' => $request->user('api')->employee_id,
                    'type' => 'out'
                ])->first();

                if ($recordLogOut) {
                    throw new ScanBarcodeException("Anda sudah melakukan absen out!");
                }

                $recordLog = Attendancelog::create([
                    'attendance_id' => $getAttendance->id,
                    'employee_id' => $request->user('api')->employee_id,
                    'attended_at' => now(),
                    'type' => 'out'
                ]);

                if ($recordLog) {
                    return $this->success(Response::HTTP_OK, 'Berhasil absen keluar', [
                        'day' => date('l, d-m-Y', strtotime($recordLog->attended_at)),
                        'time' => date('H:i:s', strtotime($recordLog->attended_at)),
                        'type' => optional($recordLog->employee)->working_time_type
                    ]);
                }
            }


            throw new ScanBarcodeException("Ulangi beberapa saat lagi");
        });
    }
}
