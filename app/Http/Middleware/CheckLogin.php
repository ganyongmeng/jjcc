<?php
/**
 * 中间件：检查管理员是否登录
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckLogin
{
    /**
     * 检查sign
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session('login')){
            if($request->ajax()){
                $res = [
                    "code" => 302,
                    "msg" => '未登录',
                ];
                return response($res,200);
            }else{
                return redirect('/login');
            }
        }
        return $next($request);
    }
}
