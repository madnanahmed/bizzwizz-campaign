<?php

namespace App\Http\Middleware;

use Closure;
use Session;

class AgnetAuth
{

    public function handle($request, Closure $next)
    {
        if (!auth()->user()->user_type == 'ag') {
            return response('Unauthorized.', 401);
            //return response()->view("errors.401",[],401);
        }
        return $next($request);
    }
}
