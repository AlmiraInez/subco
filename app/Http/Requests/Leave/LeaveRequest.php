<?php

namespace App\Http\Requests\Leave;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{
    public $validator   = null;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'leave_setting_id'  => 'required',
            'start_date'        => 'required|after_or_equal:'.date('Y-m-d'),
            'finish_date'       => 'required|after_or_equal:start_date',
        ];
    }

    public function messages()
    {
        return [
            'leave_setting_id.required'     => 'Tipe cuti wajib dipilih',
            'start_date.required'           => 'Tanggal awal wajib diisi',
            'finish_date.required'          => 'Tanggal akhir wajib diisi',
            'start_date.after_or_equal'     => 'Tanggal awal tidak boleh kurang dari tanggal hari ini',
            'finish_date.after_or_equal'    => 'Tanggal akhir tidak boleh kurang dari tanggal awal',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $this->validator    = $validator;
    }
}
