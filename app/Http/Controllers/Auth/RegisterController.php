<?php

namespace App\Http\Controllers\Auth;

use App\Customer;
use App\User;
use App\Models\Tenant;
use App\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Customer
     */
    protected function create(array $data)
    {
        // return Customer::create([
        //     'name' => $data['name'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        //     'status'=>0
        // ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'         => 'required',
            'username'     => 'required|unique:users',
            'email'        => 'required|email|unique:users',
            'password'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }
        $tenant = Tenant::create([
            'name'         => $request->name,
            'birth_date'   => $request->birth_date,
            'phone'        => $request->phone,
            'gender'       => $request->gender,
            'email'        => $request->email,
            'title'        => $request->title,
            'company_name' => $request->company_name,
            'address'      => $request->address,
        ]);
        if (!$tenant) {
            return response()->json([
                'status' => false,
                'message'     => $tenant
            ], 400);
        }
        $user = User::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'username'        => $request->username,
            'password'        => Hash::make($request->password),
            'status'          => 1 ,
            'assign_employee' => 1,
            'employee_id'     => $tenant->id
        ]);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message'     => $user
            ], 400);
        }
        $role = Role::find(3);
        $user->attachRole($role);
        return response()->json([
            'status'     => true,
            'results'     => route('admin.login'),
        ], 200);
    }
    public function showRegistrationForm()
    {
        return view('admin.register');
    }
}
