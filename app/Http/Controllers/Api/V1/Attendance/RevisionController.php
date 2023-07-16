<?php

namespace App\Http\Controllers\Api\V1\Attendance;

use App\Exceptions\InvalidAttendanceRevisionException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attendance\RevisionStoreRequest;
use App\Http\Resources\Attendance\AttendanceCollection;
use App\Http\Resources\Attendance\AttendanceResource;
use App\Http\Resources\Attendance\RevisionCollection;
use App\Http\Resources\Attendance\RevisionResource;
use App\Models\Attendance;
use App\Models\AttendanceRevision;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RevisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $attendanceRevision = AttendanceRevision::currentEmployee()
                                                ->when($request->filter_by, function(Builder $builder) use($request) {
                                                    return $this->buildFilter($request, $builder);
                                                })
                                                ->get();

        $attendanceRevision->transform(function(AttendanceRevision $attendanceRevision) {
            return new RevisionResource($attendanceRevision);
        });

        return new RevisionCollection($attendanceRevision);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RevisionStoreRequest $request)
    {
        $attendance = Attendance::find($request->attendance_id);

        if (!$attendance) {
            throw new InvalidAttendanceRevisionException;
        }

        // if () {
            
        // }

        $data = AttendanceRevision::create([
            'attendance_id' => $attendance->id,
            'attended_at' => date('Y-m-d', strtotime($attendance->attended_at)),
            'time_in' => date('H:m', strtotime($request->time_in)),
            'time_out' => date('H:m', strtotime($request->time_out)),
            'status' => 'pending',
            'reason_revision' => $request->reason_revision,
            'employee_id' => $request->user('api')->employee_id
        ]);

        if (!$data) {
            throw new InvalidAttendanceRevisionException("Gagal mengajukan revisi!");
        }

        return to_json([
            'status' => Response::HTTP_OK,
            'message' => 'revisi absensi berhasil di buat!'
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $revision)
    {
        return new AttendanceResource($revision);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $revision)
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
