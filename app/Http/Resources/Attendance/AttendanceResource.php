<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'    => $this->id,
            'date'  => date('l, d-M-Y', strtotime($this->attended_at)),
            'type'  => $this->employee->working_time_type,
            'day_in' => date('d-M-Y', strtotime($this->in_at)),
            'time_in' => date('H:m', strtotime($this->in_at)),
            $this->mergeWhen(($this->in_at != null && $this->out_at != null), [
                'day_out' => date('d-M-Y', strtotime($this->out_at)),
                'time_out' => date('H:m'),
            ]),
            'status' => $this->status,
            'logs'  => $this->logs,
        ];
    }
}
