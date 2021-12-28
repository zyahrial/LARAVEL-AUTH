<?php

namespace App\Http\Middleware;
use App\Models\Users;
use App\Http\Controllers\Response\Errors;
use Carbon\Carbon;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $token = $request->bearerToken();

        if (!$token){
            return response()->json([
                'response' => [
                    'status' => "unauthorized",
                ]
            ], 400);
        }

        $tokenParts = explode(".", $token);  
        $tokenHeader = base64_decode($tokenParts[0]);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtHeader = json_decode($tokenHeader);
        $jwtPayload = json_decode($tokenPayload);
        $date = Carbon::now();
        $timeInMilliseconds = $date->valueOf();
        $timeInMilliseconds = $timeInMilliseconds/1000;

        if ($timeInMilliseconds >= $jwtPayload->exp) {
            return response()->json([
                'response' => [
                    'status' => "unauthorized",
                ]
            ], 400);
        }

        if ($this->auth->guard($guard)->guest()) {
            return response()->json([
                'response' => [
                    'status' => "unauthorized",
                ]
            ], 400);
        }
        return $next($request);
    }
}