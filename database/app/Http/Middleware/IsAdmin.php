<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->id_role == 1) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Akses ditolak. Halaman ini hanya untuk Admin.');
    }
}