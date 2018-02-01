<?php

namespace App\Http\Controllers\Admin\System;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logic\UserLogic;
use App\Logic\MenuLogic;
use Validator;

class User extends Controller
{
    private $logic;
    public function __construct(UserLogic $logic){
        $this->logic = $logic;

    }

    public function index(){
        $tempData = [
            'title' => '账号管理',
            'active_menu_flag' => 'user',
        ];
        return view('admin/system/user/index',$tempData);
    }

    public function list(Request $req){
        $data = $req->all();
        $res = $this->logic->search($data);
        return $this->response(200,$res);
    }

    public function remove(Request $req){
        $id = $req->input('id');
        $code = $this->logic->remove($id);
        return $this->response($code);
    }

    public function changepwd(Request $req){
        $old_passwd = $req->input('old_passwd');
        $new_passwd1 = $req->input('passwd1');
        $new_passwd2 = $req->input('passwd2');
        if ($new_passwd1!==$new_passwd2){
            return $this->response(1012);
        }
        $code = $this->logic->changepwd($old_passwd,$new_passwd1);
        return $this->response($code);
    }

    public function resetpwd(Request $req){
        $id = $req->input('id');
        $code = $this->logic->resetpwd($id);
        return $this->response($code);
    }

    public function save(Request $req){
        $data = $req->all();
        //验证请求输入的数据
        $validator = Validator::make($data, [
            'account' => 'required',
            'name' => 'required',
            //'mobile' => 'required',
            'status' => 'required',
            'auths' => 'required',
        ]);
        if ($validator->fails()){//验证出现错误
            $errors = $validator->errors();
            return $this->response(303,$errors);
        }
        if (isset($data['id'])){//更新
            $code = $this->logic->update($data);
        }else{
            if ($data['passwd1']!==$data['passwd2']){
                return  $this->response(303,'两次密码不一致');
            }
            $data['passwd'] = $data['passwd1'];
            unset($data['passwd1']);
            unset($data['passwd2']);
            $code = $this->logic->add($data);
        }
        return $this->response($code);
    }
}