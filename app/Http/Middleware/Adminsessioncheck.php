<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class Adminsessioncheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (empty(session('email'))) {
            return Redirect::route('login');

        }elseif(session('role') == 'admin'){
            return $next($request); 
        }else{
            return Redirect::route('usersdashboard');
        }
    }
}
