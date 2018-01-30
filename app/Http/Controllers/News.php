<?php

namespace App\Http\Controllers;

use App\Logic\NewsLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logic\MenuLogic;
use Illuminate\Support\Facades\DB;
use Validator;

class News extends Controller
{
    public function __construct(){
    }

    public function index(){
        $tempData = [
            'title' => '新闻管理',
            'types' => NewsLogic::$types,
            'active_menu_flag' => 'content_news',
        ];
        return view('news/index',$tempData);
    }

    public function lists(Request $req){
        $data = $req->all();
        $logic = new NewsLogic();
        $res = $logic->search($data);
        return $this->response(200,$res);
    }

    public function add() {
        $tempData = [
            'title' => '添加新闻',
            'active_menu_flag' => 'content_news_add',
        ];
        return view('news/add',$tempData);
    }

    public function save(Request $req){
        $data = $req->all();
        //验证请求输入的数据
        $logic = new NewsLogic();
        if (isset($data['id'])){//更新
            $res = $logic->update($data);
        }else{
            $res = $logic->add($data);
        }
        return $this->response($res);
    }

    public function order() {
        $id = \request('id');
        $order = request('order');
        $logic = new NewsLogic();
        $res = $logic->setOrder($id, $order);
        return $this->response($res);
    }

    public function publish() {
        $id = \request('id');
        $status = \request('status');
        $logic = new NewsLogic();
        $res = $logic->setStatus($id, $status);
        return $this->response($res['code'], [], $res['msg']);
    }

    public function batchPublish(Request $req) {
        $ids = json_decode_arr($req->get('data'));
        DB::beginTransaction();
        foreach($ids as $id) {
            $logic = new NewsLogic();
            $res = $logic->setStatus($id, NewsLogic::STATUS_PUBLISHED);
            if ($res['code']!= 200) {
                DB::rollBack();
                return $this->response($res);
            }
        }
        DB::commit();
        return $this->response(200, [], '操作成功');
    }

    public function remove(Request $req){
        $id = \request('id');
        $logic = new NewsLogic();
        $res = $logic->setStatus($id, NewsLogic::STATUS_DELETED);
        return $this->response($res['code'], [], $res['msg']);
    }

    public function batchRemove(Request $req) {
        $ids = json_decode_arr($req->get('data'));
        //var_dump($ids);die();
        DB::beginTransaction();
        foreach($ids as $id) {
            $logic = new NewsLogic();
            $res = $logic->setStatus($id, NewsLogic::STATUS_DELETED);
            if ($res['code']!= 200) {
                DB::rollBack();
                return $this->response($res);
            }
        }
        DB::commit();
        return $this->response(200, [], '操作成功');
    }

    public function batchOrder(Request $req) {
        $data = json_decode_arr($req->get('data'));
        DB::beginTransaction();
        foreach($data as $v) {
            $id = $v[0];
            $order = $v[1];
            $logic = new NewsLogic();
            $res = $logic->setOrder($id, $order);
            if ($res['code']!= 200) {
                DB::rollBack();
                return $this->response($res);
            }
        }
        DB::commit();
        return $this->response(200, [], '操作成功');
    }
}