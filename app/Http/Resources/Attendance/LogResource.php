<?php

namespace App\Http\Resources\Attendance;

use Illuminate\Http\Resources\Json\JsonResource;

class LogResource extends JsonResource
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
            'attended_date' => (string) date('l, d-M-Y', strtotime($this->attended_at)),
            'attended_time' => (string) date('H:i:s', strtotime($this->attended_at)),
            'type' => $this->type,
            'working_time' => $this->employee->working_time_type
        ];
    }
}
