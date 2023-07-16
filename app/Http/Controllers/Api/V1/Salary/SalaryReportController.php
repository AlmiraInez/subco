<?php

namespace App\Http\Controllers\Api\V1\Salary;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Salary\SalaryReportCollection;
use App\Http\Resources\Salary\SalaryReportResource;
use App\Libraries\Traits\InteractWithApiResponse;
use App\Models\SalaryReport;

class SalaryReportController extends Controller
{
    use InteractWithApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $salary = SalaryReport::with(['salarydetail'])->where('employee_id', auth()->user('api')->employee_id)->anotherPeriod()->get();

        $salary->transform(function(SalaryReport $salary){
            return new SalaryReportResource($salary);
        });

        return new SalaryReportCollection($salary);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $salary = SalaryReport::with(['salarydetail'])->currentEmployee('api')->currentPeriod()->get();

        $salary->transform(function(SalaryReport $salary){
            return new SalaryReportResource($salary);
        });

        return new SalaryReportCollection($salary);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
