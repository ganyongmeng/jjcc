<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class File extends Controller
{
    public function upload(Request $request){
        $file_input_name = $request->input('file_id');//上传文件id
        $real_path = $request->file($file_input_name)->path();
        $path = $request->file($file_input_name)->store('banner');
        $filename = substr($path,strrpos($path,'/')+1);//获取本地存储后的文件名
        $localPath = 'banner/'.$filename;
        Storage::disk('uploads')->put($localPath, file_get_contents($real_path));
        return $this->response(200,['url'=>'/uploads/'.$path]);
    }
}
