<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Support\Facades\DB;
 
class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $header     = $request->header('x-access-token');

        if ($header) {
            $check =  DB::table('user')->where("token", $header)->first();            
            if (!$check) {
                return response('Token Tidak Valid.', 401);
            } else {
                return $next($request);
            }
        } else {
            return response('Silahkan Masukkan Token.', 401);
        }
    }
}