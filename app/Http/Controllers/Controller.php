<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Logic\RoleLogic;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * http请求返回结果
     * @param int     $code   返回代码 200为逻辑处理成功，其他都为逻辑处理失败
     * @param string $msg code外自定义错误信息
     * @return exit
     */
    protected function response($code = 200,$data = null,$msg = ''){
        if (is_null($data)){
            $data = new \stdClass();
        }
        $res = [
            "code" => $code,
            "msg" => empty($msg)?__('code.'.$code):$msg,
            "data" => $data
        ];
        return response($res,200);
    }

    /**
     * 检查当前登录用户是否拥有操作权限
     *
     * @param string $menu_flag 菜单唯一标识
     * @param string $op_type c：创建 u：更新 d：删除
     * @return boolean  true：有权限  false：无权限
     */
    protected function checkOpAuth($menu_flag,$op_type){
        return (new RoleLogic())->hasOpAuth($menu_flag,$op_type);
    }
}
