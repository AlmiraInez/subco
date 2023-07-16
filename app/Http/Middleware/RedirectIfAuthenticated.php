<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect('/');
        }
        // if (Auth::guard($guard)->check()) {
        //     $user = Auth::user()->id;
        //     $query = DB::table('role_user');
        //     $query->select('name');
        //     $query->leftJoin('roles', 'role_user.role_id', '=', 'roles.id');
        //     $query->where('role_user.user_id', '=', $user);
        //     $role = $query->get()->first();
        //     dd($role);
        //     switch ($role) {
        //         case 'admin':
        //             return redirect('/admin_dashboard');
        //             break;
        //         case 'seller':
        //             return redirect('/seller_dashboard');
        //             break;

        //         default:
        //             return redirect('/home');
        //             break;
        //     }
        // }

        return $next($request);
    }
}
