<?php

namespace App\Logic;

use App\Model\System\Admin;
use Illuminate\Support\Facades\DB;

class LoginLogic extends Logic
{
    /**
     * 登录处理
     *
     * @param string $account 账号
     * @param string $passwd  密码
     * @return int code 处理结果
     */
    public function handle($account,$passwd){
        $info = Admin::where(['account'=>$account,'is_delete'=>0])->first();
        if (!$info){
            return 1001;
        }
        if ($info['status']==1){
            return 1003;
        }
        if (md5(PASSWD_KEY.$passwd)==$info['passwd']){
            $info->last_login_time = date('Y-m-d H:i:s');
            $info->last_login_ip = request()->ip();
            $info->save();
            session(['login'=>$info->toArray()]);
            return 200;
        }else{
            return 1002;
        }
    }
}