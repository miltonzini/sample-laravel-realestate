<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('userRole') || Session::get('userRole') !== 'admin') {
            return redirect()->route('login')->withErrors(['error' => 'Permisos inadecuados.']);;
        }

        return $next($request);

    }
}
