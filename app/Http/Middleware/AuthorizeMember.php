<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthorizeMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        \Log::info('foo');

        dd( $request );
        try {
            $user = JWTAuth::parseToken()->authenticate();
//            $project = Project::whereUid($puid)->first();
//
//            if ($project->owner_id != $user->id) {
//                return abort(403, 'Only the project owner can create objectives');
//            }
            
//            return $next($request);
        } catch (JWTException $e) {
            return abort(401, 'Please log in to access ' . config('trellis.app.name'));
        }
    }
}
