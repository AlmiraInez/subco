<?php

namespace App\Http\Resources\Salary;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SalaryReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if (date('Y-m-01') > $this->period) {
            return [
                'period'        => Carbon::parse($this->period)->locale('id')->isoFormat('MMMM Y'),
                'basic_salary'  => number_format($this->salarydetail()->where('description', 'Basic Salary')->first()->total, 0, ',', '.'),
                'allowance'     => number_format($this->salarydetail()->where('description', '!=', 'Basic Salary')->where('type', 1)->sum('total'), 0, ',', '.'),
                'deduction'     => number_format($this->salarydetail()->where('type', 0)->sum('total'), 0, ',', '.'),
                'total'         => number_format($this->net_salary, 0, ',', '.'),
            ];
        }
        return [
            'basic_salary'  => number_format($this->salarydetail()->where('description', 'Basic Salary')->first()->total, 0, ',', '.'),
            'allowance'     => number_format($this->salarydetail()->where('description', '!=', 'Basic Salary')->where('type', 1)->sum('total'), 0, ',', '.'),
            'deduction'     => number_format($this->salarydetail()->where('type', 0)->sum('total'), 0, ',', '.'),
            'total'         => number_format($this->net_salary, 0, ',', '.'),
        ];
    }
}
