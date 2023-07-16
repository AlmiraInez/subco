<?php

namespace App\Http\Resources\Leave;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class LeaveCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status'    => Response::HTTP_OK,
            'data'      => $this->collection
        ];
    }
}
