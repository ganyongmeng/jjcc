<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logic\RoleLogic;
use App\Logic\MenuLogic;
use Validator;

class Role extends Controller
{
    private $logic;
    public function __construct(RoleLogic $logic){
        $this->logic = $logic;

    }

    public function index(){
        $tempData = [
            'title' => '角色管理',
            'active_menu_flag' => 'role',
        ];
        return view('role/index',$tempData);
    }

    public function list(Request $req){
        $data = $req->all();
        $res = $this->logic->search($data);
        return $this->response(200,$res);
    }

    public function menu(Request $req){
        $role_id = $req->input('role_id');
        $res = $this->logic->menu($role_id);
        return $this->response(200,$res);
    }

    public function option(){
        $res = $this->logic->option();
        return $this->response(200,$res);
    }

    public function save(Request $req){
        $data = $req->all();
        //验证请求输入的数据
        $validator = Validator::make($data, [
            'name' => 'required'
        ]);
        if ($validator->fails()){//验证出现错误
            $errors = $validator->errors();
            return $this->response(303,$errors);
        }
        if (isset($data['id'])){//更新
            $code = $this->logic->update($data);
        }else{
            $code = $this->logic->add($data);
        }
        
        return $this->response($code);
    }

    public function setAuth(Request $req){
        $data = $req->all();
        $code = $this->logic->setAuth($data);
        return $this->response($code);
    }

    public function remove(Request $req){
        $id = $req->input('id');
        $code = $this->logic->remove($id);
        return $this->response($code);
    }
}