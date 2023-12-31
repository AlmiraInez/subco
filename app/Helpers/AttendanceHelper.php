<?php

use App\Models\EmployeeAllowance;
use App\Models\EmployeeDetailAllowance;
use App\Models\EmployeeSalary;
use App\Models\Overtime;
use App\Models\OvertimeSchemeList;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('findShift')) {
  function findShift($array, $hour)
  {
    $attendance_in = $hour['attendance_in'] != null ? changeDateFormat('H:i:s', $hour['attendance_in']) : null;
    $attendance_out = $hour['attendance_out'] != null ? changeDateFormat('H:i:s', $hour['attendance_out']) : null;
    $interval = array();
    foreach ($array as $hours) {
      if ($attendance_in != null) {
        $interval[] = abs(strtotime($attendance_in) - strtotime($hours->start)) < abs(strtotime($attendance_in) - strtotime($hours->min_in)) ? abs(strtotime($attendance_in) - strtotime($hours->start)) : abs(strtotime($attendance_in) - strtotime($hours->min_in));
      } elseif ($attendance_out != null) {
        $interval[] = abs(strtotime($attendance_out) - strtotime($hours->finish)) < abs(strtotime($attendance_out) - strtotime($hours->max_out)) ? abs(strtotime($attendance_out) - strtotime($hours->finish)) : abs(strtotime($attendance_out) - strtotime($hours->max_out));
      }
    }

    asort($interval);
    $closest = key($interval);

    return $array[$closest];
  }
}

if (!function_exists('shiftBetween')) {
  function shiftBetween($array, $hour)
  {
    $attendance_in = $hour['attendance_in'] != null ? changeDateFormat('H:i:s', $hour['attendance_in']) : null;
    $attendance_out = $hour['attendance_out'] != null ? changeDateFormat('H:i:s', $hour['attendance_out']) : null;
    $between = array();
    if ($attendance_in != null) {
      foreach ($array as $hours) {
        if ($hours->min_in <= $attendance_in || $attendance_in <= $hours->start) {
          $between[] = $hours;
        }
      }
    } elseif ($attendance_out != null) {
      foreach ($array as $hours) {
        if ($hours->finish <= $attendance_out || $attendance_out <= $hours->max_out) {
          $between[] = $hours;
        }
      }
    } elseif (empty($between)) {
      foreach ($array as $hours) {
        if (($hours->start <= $attendance_in || $attendance_in <= $hours->finish) || ($hours->start <= $attendance_out || $attendance_out >= $hours->finish)) {
          $between[] = $hours;
        }
      }
    }
    return $between;
  }
}

if (!function_exists('roundedTime')) {
  function roundedTime($time)
  {
    return floor($time * 2) / 2;
  }
}

if (!function_exists('breaktime')) {
  function breaktime($array, $hour)
  {
    $attendance_in = $hour['attendance_in'] ? changeDateFormat('H:i:s', $hour['attendance_in']) : null;
    $attendance_out = $hour['attendance_out'] ? changeDateFormat('H:i:s', $hour['attendance_out']) : null;
    $day = changeDateFormat('Y-m-d', $hour['attendance_out']);
    $between = array();
    $breaktime = 0;
    if ($attendance_in && $attendance_out) {
      foreach ($array as $hours) {
        if (changeDateFormat('H:i', $hour['attendance_in']) > changeDateFormat('H:i', '18:00')) {
          $start_time = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $hours->start_time);
          $finish_time = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $hours->finish_time);
          if ($hour['attendance_in'] < $start_time && $finish_time < $hour['attendance_out']) {
            $between[] = $hours;
          }
        } else {
          if ($attendance_in < $hours->start_time && $hours->finish_time < $attendance_out) {
            $between[] = $hours;
          }
        }
      }
    }
    foreach ($between as $value) {
      $breaktime += $value->breaktime;
    }
    return $breaktime;
  }
}

if (!function_exists('getBreaktime')) {
  function getBreaktime($array, $hour)
  {
    $attendance_in = $hour['attendance_in'] ? changeDateFormat('H:i:s', $hour['attendance_in']) : null;
    $attendance_out = $hour['attendance_out'] ? changeDateFormat('H:i:s', $hour['attendance_out']) : null;
    $day = changeDateFormat('Y-m-d', $hour['attendance_out']);
    $between = array();
    if ($attendance_in && $attendance_out) {
      foreach ($array as $hours) {
        if (changeDateFormat('H:i', $hour['attendance_in']) > changeDateFormat('H:i', '22:00')) {
          $start_time = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $hours->start_time);
          $finish_time = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $hours->finish_time);
          if ($hour['attendance_in'] < $start_time && $finish_time < $hour['attendance_out']) {
            $between[] = abs(strtotime($hour['attendance_out']) - strtotime($finish_time));
          }
        } else {
          if ($attendance_in < $hours->start_time && $hours->finish_time < $attendance_out) {
            $between[] = abs(strtotime($attendance_out) - strtotime($hours->finish_time));
          }
        }
      }
    }

    asort($between);
    $closest = key($between);

    return $array[$closest];
  }
}

if (!function_exists('getBreaktimeOvertime')) {
  function getBreaktimeOvertime($array, $hour, $workingtime)
  {
    $datetime_in = changeDateFormat('Y-m-d H:i:s', $hour['attendance_in']);
    $datetime_out = changeDateFormat('Y-m-d H:i:s', $hour['attendance_out']);
    $time_in = changeDateFormat('H:i:s', $hour['attendance_in']);
    $time_out = changeDateFormat('H:i:s', $hour['attendance_out']);

    $time_start_shift = changeDateFormat('H:i:s', $workingtime->start);
    $time_finish_shift = changeDateFormat('H:i:s', $workingtime->finish);
    $time_min_in_shift = changeDateFormat('H:i:s', $workingtime->min_in);
    $time_max_out_shift = changeDateFormat('H:i:s', $workingtime->max_out);
    $between = array();
    $breaktime = 0;
    foreach ($array as $break) {
      $start_break = changeDateFormat('H:i:s', $break->start_time);
      $finish_break = changeDateFormat('H:i:s', $break->finish_time);
      if ($time_in > $time_out) {
        $day = changeDateFormat('Y-m-d', $datetime_out);
        $day_start_break = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $start_break);
        $day_finish_break = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $finish_break);
        $day_finish_shift = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $time_finish_shift);
        $day_max_out_shift = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $time_max_out_shift);
        if (($day_finish_shift <= $day_start_break) && ($day_finish_break <= $datetime_out)) {
          $between[] = $break;
        } else {
          continue;
        }
      } else {
        if ((($time_finish_shift <= $start_break) && ($finish_break <= $time_out))) {
          $between[] = $break;
        } else {
          continue;
        }
      }
    }

    foreach ($between as $value) {
      $breaktime += $value->breaktime;
    }
    return $breaktime;
  }
}

if (!function_exists('getBreaktimeWorkingtime')) {
  function getBreaktimeWorkingtime($array, $hour, $workingtime)
  {
    $datetime_in = changeDateFormat('Y-m-d H:i:s', $hour['attendance_in']);
    $datetime_out = changeDateFormat('Y-m-d H:i:s', $hour['attendance_out']);
    $time_in = changeDateFormat('H:i:s', $hour['attendance_in']);
    $time_out = changeDateFormat('H:i:s', $hour['attendance_out']);

    $time_start_shift = changeDateFormat('H:i:s', $workingtime->start);
    $time_finish_shift = changeDateFormat('H:i:s', $workingtime->finish);
    $time_min_in_shift = changeDateFormat('H:i:s', $workingtime->min_in);
    $time_max_out_shift = changeDateFormat('H:i:s', $workingtime->max_out);
    $between = array();
    $breaktime = 0;
    foreach ($array as $break) {
      $start_break = changeDateFormat('H:i:s', $break->start_time);
      $finish_break = changeDateFormat('H:i:s', $break->finish_time);
      // $diff = Carbon::parse($time_in)->diffInMinutes(Carbon::parse($start_break)) / 60;
      if ($time_in > $time_out) {
        $day = changeDateFormat('Y-m-d', $datetime_out);
        $day_start_break = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $start_break);
        $day_finish_break = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $finish_break);
        $day_finish_shift = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $time_finish_shift);
        $day_max_out_shift = changeDateFormat('Y-m-d H:i:s', $day . ' ' . $time_max_out_shift);
        $diff = Carbon::parse($datetime_in)->diffInMinutes(Carbon::parse($day_start_break)) / 60;
        if ($diff >= 2) {
          if ($time_out < $time_finish_shift) {
            if (($datetime_in < $day_start_break) && ($day_finish_break < $datetime_out)) {
              $between[] = $break;
            } else {
              continue;
            }
          } else {
            if (($datetime_in < $day_start_break) && ($day_finish_break < $day_finish_shift)) {
              $between[] = $break;
            } else {
              continue;
            }
          }
        } else {
          continue;
        }
      } else {
        $diff = Carbon::parse($time_in)->diffInMinutes(Carbon::parse($start_break)) / 60;
        if ($diff >= 2) {
          if ($time_out < $time_finish_shift) {
            if (($time_in < $start_break) && ($finish_break < $time_out)) {
              $between[] = $break;
            } else {
              continue;
            }
          } else {
            if (($time_in < $start_break) && ($finish_break < $time_finish_shift)) {
              $between[] = $break;
            } else {
              continue;
            }
          }
        } else {
          continue;
        }
      }
    }

    foreach ($between as $value) {
      $breaktime += $value->breaktime;
    }
    return $breaktime;
  }
}

if (!function_exists('calculateOvertime')) {
  function calculateOvertime($attendance)
  {
    if ($attendance && $attendance->adj_over_time > 0) {
      $overtime = Overtime::where('date', $attendance->attendance_date)->where('employee_id', $attendance->employee_id);
      $overtime->delete();
      $rules = OvertimeSchemeList::where('recurrence_day', $attendance->day)->get();
      $sallary = EmployeeSalary::where('employee_id', '=', $attendance->employee_id)->orderBy('created_at', 'desc')->first();
      if ($rules) {
        if ($attendance->day != 'Off') {
          $i = 0;
          $overtimes = $attendance->adj_over_time;
          $length = count($rules);
          foreach ($rules as $key => $value) {
            if ($overtimes >= 0) {
              $overtime = Overtime::create([
                'employee_id'   => $attendance->employee_id,
                'day'           => $value->recurrence_day,
                'scheme_rule'   => $value->hour,
                'hour'          => ($i != $length - 1) ? 1 : $overtimes,
                'amount'        => $value->amount,
                'basic_salary'  => $sallary ? $sallary->amount / 173 : 0,
                'date'          => changeDateFormat('Y-m-d', $attendance->attendance_date)
              ]);
            } else {
              continue;
            }
            $overtime->final_salary = $overtime->hour * $overtime->amount * $overtime->basic_salary;
            $overtime->save();
            $i++;
            $overtimes = $overtimes - 1;
            if (!$overtime) {
              return response()->json([
                'status'     => false,
                'message'     => $overtime
              ], 400);
            }
          }
        } else {
          $i = 0;
          $n = 2;
          $overtimes = $attendance->adj_over_time;
          $length = count($rules);
          foreach ($rules as $key => $value) {
            $sallary = EmployeeSalary::where('employee_id', '=', $attendance->employee_id)->orderBy('created_at', 'desc')->first();
            if ($overtimes >= 0) {
              $overtime = Overtime::create([
                'employee_id'   => $attendance->employee_id,
                'day'           => $value->recurrence_day,
                'scheme_rule'   => $value->hour,
                'hour'          => ($i != $length - 1 && $overtimes >= 1) ? 1 : $overtimes,
                'amount'        => $value->amount,
                'basic_salary'  => $sallary ? $sallary->amount / 173 : 0,
                'date'          => changeDateFormat('Y-m-d', $attendance->attendance_date)
              ]);
            } else {
              continue;
            }
            $overtime->final_salary = $overtime->hour * $overtime->amount * $overtime->basic_salary;
            $overtime->save();
            $i++;
            $overtimes = $overtimes - 1;
            if (!$overtime) {
              DB::rollBack();
              return response()->json([
                'status'     => false,
                'message'     => $overtime
              ], 400);
            }
          }
        }
      }
    } else {
      $overtime = Overtime::where('date', $attendance->attendance_date)->where('employee_id', $attendance->employee_id);
      $overtime->delete();
    }
  }
}

if (!function_exists('calculateAllowance')) {
  function calculateAllowance($attendance)
  {
    $month =  date('m', strtotime($attendance->attendance_date));
    $year =  date('Y', strtotime($attendance->attendance_date));
    $query = DB::table('attendances');
    $query->select(
      'attendances.employee_id as employee_id',
      'attendances.workingtime_id as workingtime_id',
      'attendances.attendance_date as date',
      'allowances.reccurance as reccuran',
      'employee_allowances.allowance_id as allowance_id',
      'employee_allowances.value as value',
      'employee_allowances.type as type',
      'workingtime_allowances.workingtime_id as workingtime'
    );
    $query->leftJoin('employee_allowances', 'attendances.employee_id', '=', 'employee_allowances.employee_id');
    $query->leftJoin('allowances', 'allowances.id', '=', 'employee_allowances.allowance_id');
    $query->leftJoin('workingtime_allowances', 'workingtime_allowances.allowance_id', '=', 'allowances.id');
    $query->where('attendances.id', '=', $attendance->id);
    $query->where('employee_allowances.status', '=', 1);
    $query->where('allowances.reccurance', '=', 'daily');
    $query->whereNotIn('allowances.allowance', ['Bonus Performa (KPI)']);
    $query->where('employee_allowances.month', $month);
    $query->where('employee_allowances.year', $year);
    $histories = $query->get();
    $deletedetail = EmployeeDetailAllowance::where('employee_id', $attendance->employee_id)->where('tanggal_masuk', $attendance->attendance_date);
    $deletedetail->delete();
    foreach ($histories as $history) {
      if ($history) {
        if ($history->workingtime) {
          if ($history->workingtime == $history->workingtime_id) {
            $employeedetailallowance = EmployeeDetailAllowance::create([
              'employee_id' => $history->employee_id,
              'allowance_id' => $history->allowance_id,
              'workingtime_id' => $history->workingtime_id,
              'tanggal_masuk' => $history->date,
              'value' => $history->value
            ]);

            if ($employeedetailallowance) {
              $query = EmployeeAllowance::select('employee_allowances.*');
              $query->where('employee_id', '=', $history->employee_id);
              $query->where('allowance_id', '=', $history->allowance_id);
              $query->where('month', $month);
              $query->where('year', $year);
              $updatefactor = $query->first();
              $updatequery = DB::table('employee_detailallowances');
              $updatequery->select('employee_detailallowances.*', DB::raw('count(tanggal_masuk) as date'));
              $updatequery->where('employee_detailallowances.employee_id', '=', $history->employee_id);
              $updatequery->where('employee_detailallowances.allowance_id', '=', $history->allowance_id);
              $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
              $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
              $updatequery->groupBy('employee_detailallowances.id');
              $updatecount = $updatequery->get()->count();
              if ($updatefactor) {
                $updatefactor->factor = $updatecount;
                $updatefactor->save();
              }
            }
          }
        } else {
          $employeedetailallowance = EmployeeDetailAllowance::create([
            'employee_id' => $history->employee_id,
            'allowance_id' => $history->allowance_id,
            'workingtime_id' => $history->workingtime_id,
            'tanggal_masuk' => $history->date,
            'value' => $history->value
          ]);

          if ($employeedetailallowance) {
            $month =  date('m', strtotime($attendance->attendance_date));
            $year =  date('Y', strtotime($attendance->attendance_date));
            $query = EmployeeAllowance::select('employee_allowances.*');
            $query->where('employee_id', '=', $history->employee_id);
            $query->where('allowance_id', '=', $history->allowance_id);
            $query->where('month', $month);
            $query->where('year', $year);
            $updatefactor = $query->first();
            $updatequery = DB::table('employee_detailallowances');
            $updatequery->select('employee_detailallowances.*', DB::raw('count(tanggal_masuk) as date'));
            $updatequery->where('employee_detailallowances.employee_id', '=', $history->employee_id);
            $updatequery->where('employee_detailallowances.allowance_id', '=', $history->allowance_id);
            $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
            $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
            $updatequery->groupBy('employee_detailallowances.id');
            $updatecount = $updatequery->get()->count();
            if ($updatefactor) {
              $updatefactor->factor = $updatecount;
              $updatefactor->save();
            }
          }
        }
      } else {
        DB::rollBack();
        return response()->json([
          'status'      => false,
          'message'     => $history
        ], 400);
      }
    }
    if ($attendance->workingtime_id) {
      $query = DB::table('attendances');
      $query->select(
        'employee_allowances.*',
        'attendances.employee_id as employee_id',
        'attendances.workingtime_id as workingtime_id',
        'attendances.attendance_date as date',
        'allowances.reccurance as reccuran',
        'employee_allowances.allowance_id as allowance_id',
        'attendances.adj_working_time as value',
        'employee_allowances.type as type',
        'workingtime_allowances.workingtime_id as workingtime'
      );
      $query->leftJoin('employee_allowances', 'attendances.employee_id', '=', 'employee_allowances.employee_id');
      $query->leftJoin('allowances', 'allowances.id', '=', 'employee_allowances.allowance_id');
      $query->leftJoin('workingtime_allowances', 'workingtime_allowances.allowance_id', '=', 'allowances.id');
      $query->where('attendances.id', '=', $attendance->id);
      $query->where('employee_allowances.status', '=', 1);
      $query->where('allowances.reccurance', '=', 'hourly');
      $query->where('employee_allowances.month', $month);
      $query->where('employee_allowances.year', $year);
      $query->where('employee_allowances.employee_id', '=', $attendance->employee_id);
      $hourly = $query->get();
      foreach ($hourly as $hour) {
        if ($hour) {
          if ($hour->workingtime) {
            if ($hour->workingtime == $hour->workingtime_id) {
              $employeedetailallowance = EmployeeDetailAllowance::create([
                'employee_id' => $hour->employee_id,
                'allowance_id' => $hour->allowance_id,
                'workingtime_id' => $hour->workingtime_id,
                'tanggal_masuk' => $hour->date,
                'value' => $hour->value
              ]);
  
              if ($employeedetailallowance) {
                $query = EmployeeAllowance::select('employee_allowances.*');
                $query->where('employee_id', '=', $hour->employee_id);
                $query->where('allowance_id', '=', $hour->allowance_id);
                $query->where('month', $month);
                $query->where('year', $year);
                $updatefactor = $query->first();
                $updatequery = DB::table('employee_detailallowances');
                $updatequery->where('employee_detailallowances.employee_id', '=', $hour->employee_id);
                $updatequery->where('employee_detailallowances.allowance_id', '=', $hour->allowance_id);
                $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
                $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
                $updatequery->groupBy('employee_detailallowances.id');
                $updatecount = $updatequery->get()->sum('value');
                if ($updatefactor) {
                  $updatefactor->factor = $updatecount;
                  $updatefactor->save();
                }
              }
            }
          } else {
            $employeedetailallowance = EmployeeDetailAllowance::create([
              'employee_id' => $hour->employee_id,
              'allowance_id' => $hour->allowance_id,
              'workingtime_id' => $hour->workingtime_id,
              'tanggal_masuk' => $hour->date,
              'value' => $hour->value
            ]);
  
            if ($employeedetailallowance) {
              $month =  date('m', strtotime($attendance->attendance_date));
              $year =  date('Y', strtotime($attendance->attendance_date));
              $query = EmployeeAllowance::select('employee_allowances.*');
              $query->where('employee_id', '=', $hour->employee_id);
              $query->where('allowance_id', '=', $hour->allowance_id);
              $query->where('month', $month);
              $query->where('year', $year);
              $updatefactor = $query->first();
              $updatequery = DB::table('employee_detailallowances');
              // $updatequery->select('employee_detailallowances.*', DB::raw('count(tanggal_masuk) as date'));
              $updatequery->where('employee_detailallowances.employee_id', '=', $hour->employee_id);
              $updatequery->where('employee_detailallowances.allowance_id', '=', $hour->allowance_id);
              $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
              $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
              $updatequery->groupBy('employee_detailallowances.id');
              $updatecount = $updatequery->get()->sum('value');
              if ($updatefactor) {
                $updatefactor->factor = $updatecount;
                $updatefactor->save();
              }
            }
          }
        } else {
          DB::rollBack();
          return response()->json([
            'status'      => false,
            'message'     => $hour
          ], 400);
        }
      }
    }
    // $query = EmployeeAllowance::select('employee_allowances.*');
    // $query->leftJoin('allowances', 'allowances.id', '=', 'employee_allowances.allowance_id');
    // $query->where('allowances.reccurance', '=', 'hourly');
    // $query->where('employee_allowances.employee_id', '=', $attendance->employee_id);
    // $hourly = $query->first();
    // if ($hourly) {
    //   $hourly->factor = $hourly->factor + $attendance->adj_working_time;
    //   $hourly->save();
    // }
  }
}

if (!function_exists('deleteAllowance')) {
  function deleteAllowance($attendance)
  {
    $month =  date('m', strtotime($attendance->attendance_date));
    $year =  date('Y', strtotime($attendance->attendance_date));
    $query = DB::table('attendances');
    $query->select(
      'attendances.employee_id as employee_id',
      'attendances.workingtime_id as workingtime_id',
      'attendances.attendance_date as date',
      'allowances.reccurance as reccuran',
      'employee_allowances.allowance_id as allowance_id',
      'employee_allowances.value as value',
      'employee_allowances.type as type',
      'workingtime_allowances.workingtime_id as workingtime'
    );
    $query->leftJoin('employee_allowances', 'attendances.employee_id', '=', 'employee_allowances.employee_id');
    $query->leftJoin('allowances', 'allowances.id', '=', 'employee_allowances.allowance_id');
    $query->leftJoin('workingtime_allowances', 'workingtime_allowances.allowance_id', '=', 'allowances.id');
    $query->where('attendances.id', '=', $attendance->id);
    $query->where('employee_allowances.status', '=', 1);
    $query->where('allowances.reccurance', '=', 'daily');
    $query->whereNotIn('allowances.allowance', ['Bonus Performa (KPI)']);
    $query->where('employee_allowances.month', $month);
    $query->where('employee_allowances.year', $year);
    $histories = $query->get();
    $deletedetail = EmployeeDetailAllowance::where('employee_id', $attendance->employee_id)->where('tanggal_masuk', $attendance->attendance_date);
    $deletedetail->delete();
    foreach ($histories as $history) {
      if ($history) {
        if ($history->workingtime) {
          if ($history->workingtime == $history->workingtime_id) {
            $month =  date('m', strtotime($attendance->attendance_date));
            $year =  date('Y', strtotime($attendance->attendance_date));
            $query = EmployeeAllowance::select('employee_allowances.*');
            $query->where('employee_id', '=', $history->employee_id);
            $query->where('allowance_id', '=', $history->allowance_id);
            $query->where('month', $month);
            $query->where('year', $year);
            $updatefactor = $query->first();
            $updatequery = DB::table('employee_detailallowances');
            $updatequery->select('employee_detailallowances.*', DB::raw('count(tanggal_masuk) as date'));
            $updatequery->where('employee_detailallowances.employee_id', '=', $history->employee_id);
            $updatequery->where('employee_detailallowances.allowance_id', '=', $history->allowance_id);
            $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
            $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
            $updatequery->groupBy('employee_detailallowances.id');
            $updatecount = $updatequery->get()->count();
            if ($updatefactor) {
              $updatefactor->factor = $updatecount;
              $updatefactor->save();
            }
          }
        } else {
          $month =  date('m', strtotime($attendance->attendance_date));
          $year =  date('Y', strtotime($attendance->attendance_date));
          $query = EmployeeAllowance::select('employee_allowances.*');
          $query->where('employee_id', '=', $history->employee_id);
          $query->where('allowance_id', '=', $history->allowance_id);
          $query->where('month', $month);
          $query->where('year', $year);
          $updatefactor = $query->first();
          $updatequery = DB::table('employee_detailallowances');
          $updatequery->select('employee_detailallowances.*', DB::raw('count(tanggal_masuk) as date'));
          $updatequery->where('employee_detailallowances.employee_id', '=', $history->employee_id);
          $updatequery->where('employee_detailallowances.allowance_id', '=', $history->allowance_id);
          $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
          $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
          $updatequery->groupBy('employee_detailallowances.id');
          $updatecount = $updatequery->get()->count();
          if ($updatefactor) {
            $updatefactor->factor = $updatecount;
            $updatefactor->save();
          }
        }
      } else {
        DB::rollBack();
        return response()->json([
          'status'      => false,
          'message'     => $history
        ], 400);
      }
    }
    $query = DB::table('attendances');
    $query->select(
      'employee_allowances.*',
      'attendances.employee_id as employee_id',
      'attendances.workingtime_id as workingtime_id',
      'attendances.attendance_date as date',
      'allowances.reccurance as reccuran',
      'employee_allowances.allowance_id as allowance_id',
      'attendances.adj_working_time as value',
      'employee_allowances.type as type',
      'workingtime_allowances.workingtime_id as workingtime'
    );
    $query->leftJoin('employee_allowances', 'attendances.employee_id', '=', 'employee_allowances.employee_id');
    $query->leftJoin('allowances', 'allowances.id', '=', 'employee_allowances.allowance_id');
    $query->leftJoin('workingtime_allowances', 'workingtime_allowances.allowance_id', '=', 'allowances.id');
    $query->where('attendances.id', '=', $attendance->id);
    $query->where('employee_allowances.status', '=', 1);
    $query->where('allowances.reccurance', '=', 'hourly');
    $query->where('employee_allowances.month', $month);
    $query->where('employee_allowances.year', $year);
    $query->where('employee_allowances.employee_id', '=', $attendance->employee_id);
    $hourly = $query->get();
    foreach ($hourly as $hour) {
      if ($hour) {
        if ($hour->workingtime) {
          if ($hour->workingtime == $hour->workingtime_id) {
            $employeedetailallowance = EmployeeDetailAllowance::create([
              'employee_id' => $hour->employee_id,
              'allowance_id' => $hour->allowance_id,
              'workingtime_id' => $hour->workingtime_id,
              'tanggal_masuk' => $hour->date,
              'value' => $hour->value
            ]);

            if ($employeedetailallowance) {
              $query = EmployeeAllowance::select('employee_allowances.*');
              $query->where('employee_id', '=', $hour->employee_id);
              $query->where('allowance_id', '=', $hour->allowance_id);
              $query->where('month', $month);
              $query->where('year', $year);
              $updatefactor = $query->first();
              $updatequery = DB::table('employee_detailallowances');
              $updatequery->where('employee_detailallowances.employee_id', '=', $hour->employee_id);
              $updatequery->where('employee_detailallowances.allowance_id', '=', $hour->allowance_id);
              $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
              $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
              $updatequery->groupBy('employee_detailallowances.id');
              $updatecount = $updatequery->get()->sum('value');
              if ($updatefactor) {
                $updatefactor->factor = $updatecount;
                $updatefactor->save();
              }
            }
          }
        } else {
          $employeedetailallowance = EmployeeDetailAllowance::create([
            'employee_id' => $hour->employee_id,
            'allowance_id' => $hour->allowance_id,
            'workingtime_id' => $hour->workingtime_id,
            'tanggal_masuk' => $hour->date,
            'value' => $hour->value
          ]);

          if ($employeedetailallowance) {
            $month =  date('m', strtotime($attendance->attendance_date));
            $year =  date('Y', strtotime($attendance->attendance_date));
            $query = EmployeeAllowance::select('employee_allowances.*');
            $query->where('employee_id', '=', $hour->employee_id);
            $query->where('allowance_id', '=', $hour->allowance_id);
            $query->where('month', $month);
            $query->where('year', $year);
            $updatefactor = $query->first();
            $updatequery = DB::table('employee_detailallowances');
            // $updatequery->select('employee_detailallowances.*', DB::raw('count(tanggal_masuk) as date'));
            $updatequery->where('employee_detailallowances.employee_id', '=', $hour->employee_id);
            $updatequery->where('employee_detailallowances.allowance_id', '=', $hour->allowance_id);
            $updatequery->whereRaw("extract( month from employee_detailallowances.tanggal_masuk) = $month");
            $updatequery->whereRaw("extract( year from employee_detailallowances.tanggal_masuk) = $year");
            $updatequery->groupBy('employee_detailallowances.id');
            $updatecount = $updatequery->get()->sum('value');
            if ($updatefactor) {
              $updatefactor->factor = $updatecount;
              $updatefactor->save();
            }
          }
        }
      } else {
        DB::rollBack();
        return response()->json([
          'status'      => false,
          'message'     => $hour
        ], 400);
      }
    }
  }
}

if(!function_exists('decimalHours')){
  function decimalHours($time)
  {
      $hms = explode(":", $time);
      return ($hms[0] + ($hms[1]/60) + ($hms[2]/3600));
  }
}