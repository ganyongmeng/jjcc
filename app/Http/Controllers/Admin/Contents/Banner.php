<?php

namespace App\Http\Controllers\Admin\Contents;

use App\Http\Controllers\File;
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
        $filelist = empty($banner['filelist'])?'':array_values(unserialize($banner['filelist']));
        $info = [
            'id'=>empty($banner['id'])?'':$banner['id'],
            'b_time'=>empty($banner['b_time'])?5:$banner['b_time'],
            'filelists'=>$filelist
        ];
        $tempData = [
            'title' => 'banner管理',
            'active_menu_flag' => 'banner',
            'info'=>json_encode($info),
        ];
        return view('admin/contents/banner/index',$tempData);
    }

    public function banneredit(Request $request){
        $data = $request->all();
        $validate = Validator::make($data,[
            'filelist'=>'required',
        ]);
        if(!empty($data['filelist'])){
            foreach ($data['filelist'] as $k=>&$v){
                if(empty($v)){
                    unset($data['filelist'][$k]);
                    array_values($data['filelist']);
                }
            }
        }

        if($validate->fails()){
            return $this->response(10001,$validate->errors());
        }
        $bool = $this->bannerLogic->editBanner($data);

        if($bool){
            return $this->response(200);
        }
        return $this->response(10000);
    }

    public function delbanner(Request $request){
        $data = $request->all();
        $validate = Validator::make($data,[
            'id'=>'required',
            'filename'=>'required',
            'is_delete'=>'required',
        ]);
        if($validate->fails()){
            return $this->response(10001,$validate->errors());
        }

        if(!empty($data['filelist'])){
            foreach ($data['filelist'] as $k=>&$v){
                if(empty($v)){
                    unset($data['filelist'][$k]);
                    array_values($data['filelist']);
                }
            }
        }

        //删除文件
        if(!empty($data['is_delete'])){
            $file = new File();
            $filename = empty($data['filename'])?'':$data['filename'];
            if(empty($filename)){
                return $this->response(10002);
            }
            $file->delfile($filename);
        }
        $bool = $this->bannerLogic->editBanner($data);
        if($bool){
            return $this->response(200);
        }
        return $this->response(10000);
    }

}