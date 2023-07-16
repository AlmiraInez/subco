<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Attendance\AttendanceCollection;
use App\Http\Resources\Attendance\AttendanceResource;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attendance = Attendance::currentEmployee()
                            ->when($request->filter_by, function(Builder $builder) use($request) {
                                return $this->buildFilter($request, $builder);
                            })
                            ->get();

        $attendance->transform(function(Attendance $attendance) {
            return new AttendanceResource($attendance);
        });

        return new AttendanceCollection($attendance);
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
    public function show(Attendance $attendance)
    {
        return new AttendanceResource($attendance);
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

    protected function buildFilter($request, $builder)
    {
        if ($request->filter_by == 'day') {
            $q = $builder->where('attended_at', date('Y-m-d'));
        } elseif ($request->filter_by == 'month') {
            $q = $builder->whereBetWeen('attended_at', [
                date('Y-m-01'),
                date('Y-m-t')
            ]);
        } elseif ($request->filter_by == 'week') {
            $now = Carbon::now();
            $start = $now->startOfWeek()->format("Y-m-d");
            $end = $now->endOfWeek()->format('Y-m-d');
            
            $q = $builder->whereBetWeen('attended_at', [$start, $end]);

        } else {
            $q = $builder->whereBetWeen('attended_at', [
                date('Y-01-01'),
                date('Y-12-t')
            ]);
        }

        return $q;
    }
}
