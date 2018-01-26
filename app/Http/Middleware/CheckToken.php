<?php
/**
 * 中间件：检查token检验
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class CheckToken
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
        $header = [
            'Access-Control-Allow-Origin' => '*',
            'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, token',
            'Access-Control-Allow-Methods' => 'GET, POST, PUT, OPTIONS, DELETE' 
        ];
        $token = $request->header('token');
        if (empty($token)){
            $token = $request->input('token');
        }
        if(empty($token)){
            $res = ['code' => 10001,'err_msg' =>'请传入token'];
            return response($res,200,$header,'json');
        }
        $info = Redis::get(TOKEN);
        if (empty($info)){
            Redis::setex(TOKEN,TOKEN_TIME,MD5(TOKEN_SIGN.time()));
            $res = ['code' => 10001,'err_msg' =>'token已过期，请重新获取'];
            return response($res,200,$header,'json');
        }
        if($token != $info){
            $res = ['code' => 10001,'err_msg' =>'token校验错误，请重新获取'];
            return response($res,200,$header,'json');
        }

        return $next($request);
    }
}
