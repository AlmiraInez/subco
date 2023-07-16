<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\HolidayAllowanceRule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class HolidayAllowanceRuleController extends Controller
{
    public function __construct()
    {
        View::share('menu_active', url('admin/' . 'holidayallowancerule'));
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $allowance_id = $request->allowance_id;

        //Count Data
        $query = DB::table('holiday_allowance_rules');
        $query->select('holiday_allowance_rules.*');
        $query->where('allowance_id', $allowance_id);
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('holiday_allowance_rules');
        $query->select(
            'holiday_allowance_rules.*',
            'allowances.allowance as allowance_name'
        );
        $query->leftJoin('allowances', 'allowances.id', '=', 'holiday_allowance_rules.allowance_id');
        $query->where('allowance_id', $allowance_id);
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $allowancerules = $query->get();

        $data = [];
        foreach ($allowancerules as $allowancerule) {
            $allowancerule->no = ++$start;
            $data[] = $allowancerule;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator = Validator::make($request->all(), [
            'first'    => 'required|integer',
            'until'    => 'required|integer',
            'type' 	   => 'required',
            'value'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $rule = HolidayAllowanceRule::create([
            'allowance_id' => $request->allowance_id,
            'first'        => $request->first,
            'until'        => $request->until,
            'type' 	       => $request->type,
            'value'        => $request->value,
        ]);

        if (!$rule) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message'     => $rule
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success add data'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rule = HolidayAllowanceRule::with('allowance')->find($id);
        return response()->json([
            'status'     => true,
            'data' => $rule
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'first'    => 'required|integer',
            'until'    => 'required|integer',
            'type'        => 'required',
            'value'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $rule = HolidayAllowanceRule::find($id);
        $rule->allowance_id = $request->allowance_id;
        $rule->first        = $request->first;
        $rule->until        = $request->until;
        $rule->type         = $request->type;
        $rule->value        = $request->value;
        $rule->save();

        if (!$rule) {
            return response()->json([
                'status' => false,
                'message'     => $rule
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => $rule
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $rule = HolidayAllowanceRule::find($id);
            $rule->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'     => false,
                'message'     =>  'Data has been used to another page'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete data'
        ], 200);
    }
}
