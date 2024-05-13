<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EnsureStoreMatches
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
      $storeId = $request->route('storeId');
      $sessionStoreId = Session::get('store.id');

      if ($sessionStoreId && $sessionStoreId != $storeId) {
          return redirect()->route('store', ['storeId' => $sessionStoreId])
              ->with('error', 'No puedes acceder a una tienda diferente a la seleccionada.');
      }

      return $next($request);
    }
}
