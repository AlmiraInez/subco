<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('admin')->check()) {
            // $user = Auth::user()->id;
            // $query = DB::table('role_user');
            // $query->select('name');
            // $query->leftJoin('roles', 'role_user.role_id', '=', 'roles.id');
            // $query->where('role_user.user_id', '=', $user);
            // $role = $query->get()->first();
            return redirect('/admin/dashboard');
        }
        return $next($request);
    }
}
