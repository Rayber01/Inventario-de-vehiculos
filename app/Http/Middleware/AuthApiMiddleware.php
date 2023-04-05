<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clavePrivada = $request->header('PK');
        if ($clavePrivada === 'MTQ2DHmT5nLXW92BRjb7s9CjQ') {
            return $next($request);
        }
        return response()->json(['mensaje' => 'No autorizado'], 401);
    }
}
