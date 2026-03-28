<?php

namespace App\Http\Middleware;

use App\Models\AppData;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnableDesawar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $enableDesawar = AppData::pluck('enable_desawar')->first();

        if (!$enableDesawar) {
            abort(404);
        }
        return $next($request);
    }
}
