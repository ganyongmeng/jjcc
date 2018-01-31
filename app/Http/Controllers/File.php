<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class File extends Controller
{
    public function upload(Request $request){
        $file_input_name = $request->input('file_id');//上传文件id
        $module = $request->input('module');//所属模块
        $real_path = $request->file($file_input_name)->path();
        $uploadfilename = $request->file($file_input_name)->getClientOriginalName();//上传文件名
        $path = $request->file($file_input_name)->store($module);
        $filename = substr($path,strrpos($path,'/')+1);//获取本地存储后的文件名
        $localPath = $module.'/'.$filename;
        Storage::disk('uploads')->put($localPath, file_get_contents($real_path));
        $root = $_SERVER['DOCUMENT_ROOT'] ;
        $wl_path = $root.'/'.$filename;
        $filedata = [
            'client_name'=>$uploadfilename,
            'server_name'=>$filename,
            'file_pathname'=>$localPath,
            'full_pathname'=>$wl_path,
            'create_time'=>date('Y-m-d H:i:s',time()),
        ];
        \App\Model\File::insert($filedata);
        return $this->response(200,['url'=>'/uploads/'.$path]);
    }

    public function delfile($filename){
        $root = $_SERVER['DOCUMENT_ROOT'];
//        Storage::delete($root.$filename);
        @unlink($root.'/'.$filename);
        return $this->response(200);

    }
}
