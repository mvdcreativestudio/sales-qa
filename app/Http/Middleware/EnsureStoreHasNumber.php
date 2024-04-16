<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStoreHasNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->store->phoneNumber) {
            session()->flash('error', 'No puedes acceder al modulo si tu tienda no tiene un número de teléfono.');
            return redirect()->back();
        }
        return $next($request);
    }
}
