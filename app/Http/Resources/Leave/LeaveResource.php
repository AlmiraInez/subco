<?php

namespace App\Http\Resources\Leave;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveResource extends JsonResource
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
            'id'                    => $this->id,
            'start_date'            => date('d M Y', strtotime($this->log()->orderBy('date', 'asc')->first()->date)),
            'finish_date'           => date('d M Y', strtotime($this->log()->orderBy('date', 'desc')->first()->date)),
            'total_leave_day'       => $this->log()->get()->count(),
            'leave_type_name'       => $this->leavesetting->leave_name,
            'short_of_leave_name'   => substr($this->leavesetting->leave_name, 0, 1),
            'leave_paid_unpaid'     => $this->leavesetting->description == 1 ? 'Paid' : 'Unpaid,'
        ];
    }
}
