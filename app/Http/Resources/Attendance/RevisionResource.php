<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;

class RevisionResource extends JsonResource
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
            'id' => $this->id,
            'date' => date('l, d M Y', strtotime($this->attended_at)),
            'time_in_before' => date('H:m', strtotime($this->attendance->in_at)),
            'time_out_before' => date('H:m', strtotime($this->attendance->out_at)),
            'time_in_after' => date('H:m', strtotime($this->time_in)),
            'time_out_after' => date('H:m', strtotime($this->time_out)),
            'status' => ucfirst($this->status),
            'working_type_before' => 'Shift',
            'working_type_after' => 'Non - Shift',
        ];
    }
}
