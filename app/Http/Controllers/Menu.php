<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logic\MenuLogic;
use Validator;

class Menu extends Controller
{
    private $logic;
    public function __construct(MenuLogic $logic){
        $this->logic = $logic;
    }

    public function index(){
        $tempData = [
            'title' => '菜单管理',
            'active_menu_flag' => 'menu',
        ];
        return view('menu/index',$tempData);
    }

    public function list(Request $req){
        $data = $req->all();
        $res = $this->logic->search($data);
        return $this->response(200,$res);
    }

    public function option(){
        $res = $this->logic->tree();
        return $this->response(200,$res);
    }

    public function getParentMenuOption(){
        $res = $this->logic->option();
        return $this->response(200,$res);
    }

    public function save(Request $req){
        $data = $req->all();
        //验证请求输入的数据
        $validator = Validator::make($data, [
            'pid' => 'required',
            'name' => 'required',
            'link' => 'required_unless:pid,0',
            'seq' => 'bail|required|integer',
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

    public function remove(Request $req){
        $id = $req->input('id');
        $code = $this->logic->remove($id);
        return $this->response($code);
    }
}