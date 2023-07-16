<?php

namespace App\Http\Resources\Salary;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class SalaryReportCollection extends ResourceCollection
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
