<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ScanOutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }

    public function scanOut()
    {
        $this->ensureIsHasScannedIn();
    }

    protected function ensureIsHasScannedIn()
    {
        $getLogAttendance = AttendanceLog::where([
            'employee_id' => Auth::user('api')->employee_id,
            'attended_at' => date('Y-m-d')
        ])->first();

        if (!$getLogAttendance) {
            throw new ScanBarcodeException("Anda dari tadi ngapain aja belum absen masuk!");
        }

        if (!is_null($getLogAttendance->out_at)) {
            throw new ScanBarcodeException("Scan pisan ae bro!");
        }

        if ($getLogAttendance->update(['out_at' => now()])) {
            return $this->success($getLogAttendance);
        }

        throw new ScanBarcodeException("Terjadi Kesalahan, ulangi beberapa saat lagi");
    }
}
