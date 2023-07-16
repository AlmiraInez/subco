<?php

namespace App\Http\Controllers\Api\V1\Leave;

use App\Exceptions\LeaveException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Leave\LeaveRequest;
use App\Http\Resources\Leave\LeaveCollection;
use App\Http\Resources\Leave\LeaveResource;
use App\Http\Resources\Leave\LeaveStatusCollection;
use App\Http\Resources\Leave\LeaveStatusResource;
use App\Models\CalendarException;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\LeaveLog;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Libraries\Traits\InteractWithApiResponse;
use App\Models\LeaveDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class LeaveController extends Controller
{
    use InteractWithApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $leave  = Leave::with(['leavesetting'])->when($request->type, function(Builder $builder) use($request) {
            $builder->whereHas('log', function(Builder $q) use ($request) {
                return $this->buildFilter($request, $q);
            });
        })->currentEmployee()->get();

        $leave->transform(function(Leave $leave) {
            return new LeaveResource($leave);
        });

        return new LeaveCollection($leave);
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
    public function store(LeaveRequest $request)
    {
        return DB::transaction(function() use ($request) {
            if ($request->validator && $request->validator->fails()) {
                return $this->error($request->validator->getMessageBag()->first());
            }
            $data   = $this->exceptionDate($request);
            $returnData     = [
                'start_date'    => date('d-m-Y', strtotime($request->start_date)),
                'finish_date'   => date('d-m-Y', strtotime($request->finish_date)),
            ];

            $createLeave    = Leave::create([
                'employee_id'       => $request->user('api')->employee_id,
                'status'            => 0,
                'notes'             => $request->description,
                'duration'          => count($data),
                'leave_setting_id'  => $request->leave_setting_id,
            ]);

            if ($createLeave) {
                $logData    = [];
                $request->used_balance = 0;
                foreach ($data as $key => $value) {
                    $logData[]  = [
                        'leave_id'  => $createLeave->id,
                        'type'      => $value['type'],
                        'start'     => $value['start_time'],
                        'finish'    => $value['finish_time'],
                        'date'      => $value['date'],
                    ];
                    $request->used_balance += 1;
                }
                $this->adjustLeaveBalance($request);
                $createLog = $createLeave->log()->createMany($logData);
                
                if (!$createLog) {
                    throw new LeaveException("Error create log");
                }
            }

            return $this->success(200, "Success submit leave request.", $returnData);
        });
    }

    public function adjustLeaveBalance(Request $request)
    {
        $leaveBalance   = LeaveDetail::where('leavesetting_id', $request->leave_setting_id)->where('employee_id', $request->user('api')->employee_id)->where('from_balance', '<=', dbDate($request->start_date))->where('to_balance', '>=', dbDate($request->start_date))->where('from_balance', '<=', dbDate($request->finish_date))->where('to_balance', '>=', dbDate($request->finish_date))->first();
        if ($leaveBalance) {
            $leaveBalance->used_balance = $leaveBalance->used_balance + $request->used_balance;
            $overBalance    = abs($leaveBalance->remaining_balance - $leaveBalance->used_balance);
            if ($leaveBalance->balance != -1) {
                $leaveBalance->over_balance = (($leaveBalance->remaining_balance - $leaveBalance->used_balance) <= 0) ? $leaveBalance->over_balance + $overBalance : 0;
                $leaveBalance->remaining_balance    = (($leaveBalance->remaining_balance - $leaveBalance->used_balance) <= 0) ? 0 : $leaveBalance->remaining_balance - $leaveBalance->used_balance;
            }
            $leaveBalance->save();
        } else {
            throw new LeaveException("Saldo cuti anda tidak ditemukan harap hubungi admin!");
        }
    }

    public function exceptionDate(Request $request)
    {
        $start  = dbDate($request->start_date);
        $finish = dbDate($request->finish_date);
        $routine= '1 day';
        $dates  = getDatesFromRange($start, $finish, 'Y-m-d', $routine);

        $employeeCalendar   = Employee::find($request->user('api')->employee_id);

        $calendar   = CalendarException::where('calendar_id', $employeeCalendar->calendar_id)->get();
        if (!$calendar) {
            throw new LeaveException("Anda belum memiliki kalendar kerja. Silahkan menghubungi admin untuk memperbarui data");
            
        }
        $exceptionDate  = [];
        foreach ($calendar as $date) {
            $exceptionDate[]    = $date->date_exception;
        }

        $useDate    = LeaveLog::whereHas('leave', function($q) use ($request) {
            $q->where('employee_id', $request->user('api')->employee_id);
            $q->where('status', '<>', 2);
        })->get();
        $usingDate  = [];
        foreach ($useDate as $key => $date) {
            $usingDate[]    = $date->date;
        }

        $data   = [];
        foreach ($dates as $key => $date) {
            if (in_array($date, $exceptionDate)) {
                continue;
            } elseif (in_array($date, $usingDate)) {
                throw new LeaveException("Anda telah mengajukan cuti ditanggal yang sama. Silahkan cek pengajuan sebelumnya.");
            } else {
                $data[] = [
                    'date'          => $date,
                    'type'          => 'fullday',
                    'start_time'    => '09:00:00',
                    'finish_time'   => '17:00:00',
                ];
            }
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $leave  = Leave::with(['leavesetting'])->when($request->type, function(Builder $builder) use($request) {
            $builder->whereHas('log', function(Builder $q) use ($request) {
                return $this->buildFilter($request, $q);
            });
        })->currentEmployee()->get();

        $leave->transform(function(Leave $leave) {
            return new LeaveStatusResource($leave);
        });

        return new LeaveStatusCollection($leave);
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

    protected function buildFilter($request, $builder)
    {
        if ($request->type == 'day') {
            $q = $builder->where('date', date('Y-m-d'));
        } elseif ($request->type == 'month') {
            $q = $builder->whereBetWeen('date', [
                date('Y-m-01'),
                date('Y-m-t')
            ]);
        } elseif ($request->type == 'week') {
            $now = Carbon::now();
            $start = $now->startOfWeek()->format("Y-m-d");
            $end = $now->endOfWeek()->format('Y-m-d');
            
            $q = $builder->whereBetWeen('date', [$start, $end]);

        } else {
            $q = $builder->whereBetWeen('date', [
                date('Y-01-01'),
                date('Y-12-t')
            ]);
        }

        return $q;
    }
}
