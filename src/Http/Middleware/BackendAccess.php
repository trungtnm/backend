<?php

namespace Trungtnm\Backend\Http\Middleware;


class BackendAccess
{
    public function handle($request, \Closure $next, $role = null)
    {
        if (\Sentinel::check()) {
            if (empty($role) || \Sentinel::hasAccess([$role])) {
                return $next($request);
            }
        }
        if (empty($role)) {
            return redirect(route('loginBackend'))->withErrors('Please login first');
        } else {
            return redirect(route('accessDenied'))->withErrors('Permission denied.');
        }

        return $next($request);
    }
}