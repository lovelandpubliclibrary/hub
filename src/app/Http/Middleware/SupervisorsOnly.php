<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;
use App\Role;

class SupervisorsOnly
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
        $supervisor_role = Role::where('role', 'Supervisor')->first();

        if (!Auth::user()->hasRole($supervisor_role)) {
            Session::flash('message', 'You do not have the proper permissions to view ' . $request->url() . '.');
            return back();
        }
        return $next($request);
    }
}
