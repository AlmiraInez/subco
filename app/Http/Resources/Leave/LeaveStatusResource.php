<?php

namespace App\Http\Resources\Leave;

use Illuminate\Http\Resources\Json\JsonResource;

class LeaveStatusResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $status     = [
            -1  => 'Draft',
            0   => 'Waiting Approval',
            1   => 'Approved',
            2   => 'Rejected'
        ];
        return [
            'status'        => $status[$this->status],
            'start_date'    => date('d M Y', strtotime($this->log()->orderBy('date', 'asc')->first()->date)),
            'finsih_date'   => date('d M Y', strtotime($this->log()->orderBy('date', 'desc')->first()->date)),
            'leave_type'    => $this->leavesetting->leave_name,
            'description'   => $this->notes,
        ];
    }
}
