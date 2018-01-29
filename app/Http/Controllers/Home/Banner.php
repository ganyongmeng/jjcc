<?php

namespace App\Http\Controllers\Home;

use App\Logic\BannerLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class Banner extends Controller
{
    private $logic;
    private $bannerLogic;
    public function __construct(){
        $this->bannerLogic = new BannerLogic();
    }

    public function index(){
        $banner = $this->bannerLogic->bannerinfo();
        $banner = (array)$banner;
        $info = [
            'b_time'=>empty($banner['b_time'])?5:$banner['b_time'],
            'filelists'=>empty($banner['filelist'])?'':unserialize($banner['filelist'])
        ];
        $tempData = [
            'title' => 'banner管理',
            'active_menu_flag' => '',
            'info'=>json_encode($info),
        ];
        return view('home/banner/index',$tempData);
    }

    public function banneredit(Request $request){
        $data = $request->all();
        $validate = Validator::make($data,[
            'filelist'=>'required',
        ]);

        foreach ($data['filelist'] as $k=>&$v){
            if(empty($v)){
                unset($data['filelist'][$k]);
                array_values($data['filelist']);
            }
        }
        $data['id']=1;
        if($validate->fails()){
            return $this->response(10001,$validate->errors());
        }
        $bool = $this->bannerLogic->editBanner($data);
        if($bool){
            return $this->response(200);
        }
        return $this->response(10000);
    }

}