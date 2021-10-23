<?php

namespace App\Http\Middleware;

use App\Models\RestaurantUsers;
use App\Models\VendorUsersHistory;
use App\Traits\ApiResponse;
use Closure;

class RestaurantUserMiddleware
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
                return $next($request);
            }
            return $this->sendError('unauthenticated',401);
        } else {
            return $this->sendError('unauthenticated',401);
        }
    }

    private function checkToken($token){
        $response = VendorUsersHistory::where('token_login',$token)->first();
        if ($response){
            return $response;
        }
        return false;
    }
}
