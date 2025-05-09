<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class hasAccessMiddlware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $committee = $user->committees()->first();
        if (!$committee) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        if ($user->is_super_admin) {
            return $next($request);
        }

        if (!$committee) {
            return response()->json(['message' => 'Committee not found'], 404);
        }
        $pivotStatus = $committee->pivot->status;


        if ($pivotStatus === 'inactive') {
            return response()->json(['message' => 'Your membership in this committee is inactive'], 403);
        }

        return $next($request);
    }
}
