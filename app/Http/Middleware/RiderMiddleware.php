<?php

namespace App\Http\Middleware;

use App\Models\Riders;
use App\Traits\ApiResponse;
use App\User;
use Closure;

class RiderMiddleware
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->header('token-login')??null;
        if (!empty($token))
        {
            $user = $this->checkToken($token);
            if ($user){
//                config(['api.global.user'=>$user->toArray()]);
                return $next($request);
            }
            return $this->sendError('unauthenticated',401);
        } else {
            return $this->sendError('unauthenticated',401);
        }
    }

    private function checkToken($token){
        $response = Riders::where('token',$token)->first();
        if ($response){
            return $response;
        }
        return false;
    }
}
