<?php

namespace App\Http\Middleware;

use App\Models\ClientSources;
use App\Traits\ApiResponse;
use Closure;

class CheckClientApiMiddleware
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
        $token = $request->header('token')??null;
        if (!empty($token))
        {
            if ($this->checkToken($token)){
                return $next($request);
            }
            return $this->sendError('unauthenticated',401);
        } else {
            return $this->sendError('unauthenticated',401);
        }
    }

    private function checkToken($token){
        $response = ClientSources::where('token',$token)->get();
        if ($response->count()){
            return true;
        }
        return false;
    }
}
