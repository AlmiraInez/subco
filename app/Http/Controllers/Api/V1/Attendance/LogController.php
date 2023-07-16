<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Attendance\LogCollection;
use App\Http\Resources\Attendance\LogResource;
use Illuminate\Database\Eloquent\Builder;
use App\Models\AttendanceLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $log = AttendanceLog::currentEmployee('api')
                ->when((date('Y-m-d', strtotime($request->start_date)) == date('Y-m-d')) && (date('Y-m-d', strtotime($request->end_date)) == date('Y-m-d')), function(Builder $builder) use ($request) {
                    return $builder->where('attended_at', date('Y-m-d', strtotime($request->start_date)));
                })
                ->when(($request->start_date && $request->end_date), function(Builder $builder) use($request) {
                    $start_date = date('Y-m-d', strtotime($request->start_date));
                    $end_date = date('Y-m-d', strtotime($request->end_date));
                    return $builder->whereBetween('attended_at', [$start_date, $end_date]);
                })
                ->get();

        $log->transform(function(AttendanceLog $log) {
            return new LogResource($log);
        });

        return new LogCollection($log);
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
    public function show(AttendanceLog $log)
    {
        return new LogResource($log);
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
