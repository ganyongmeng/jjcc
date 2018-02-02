<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Storage;

class File extends Controller
{
    private function _save($request, $file_input_name, $module) {
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

        return '/uploads/'.$path;
    }

    public function upload(Request $request){
        $file_input_name = $request->input('file_id');//上传文件id
        $module = $request->input('module');//所属模块
        $path = $this->_save($request, $file_input_name, $module);

        return $this->response(200,['url'=>$path]);
    }

    public function delfile($filename){
        $root = $_SERVER['DOCUMENT_ROOT'];
//        Storage::delete($root.$filename);
        //var_dump(unlink($root.'/'.$filename));die();
        @unlink($root.'/'.$filename);
        return $this->response(200);

    }

    public function delByEditor(Request $request) {
        return $this->delfile($request->input('src'));
    }

    public function uploadByEditor(Request $request) {
        $file_input_name = $request->input('file_id');//上传文件id
        $module = $request->input('module');//所属模块

        $path = $this->_save($request, $file_input_name, $module);
        return response()->json(['link'=>$path]);

        /*
        // Allowed extentions.
        $allowedExts = array("gif", "jpeg", "jpg", "png");
        // Get filename.
        //var_dump($_FILES["file"]["name"]);die();
        $temp = explode(".", $_FILES["file"]["name"]);
        // Get extension.
        $extension = end($temp);
        // An image check is being done in the editor but it is best to
        // check that again on the server side.
        // Do not use $_FILES["file"]["type"] as it can be easily forged.
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $_FILES["file"]["tmp_name"]);
        if ((($mime == "image/gif")    || ($mime == "image/jpeg")    || ($mime == "image/pjpeg")    || ($mime == "image/x-png")    || ($mime == "image/png"))    && in_array($extension, $allowedExts)) {
            // Generate new random name.
            $name = sha1(microtime()) . "." . $extension;
            // Save file in the uploads folder.
            move_uploaded_file($_FILES["file"]["tmp_name"], "../uploads/" . $name);
            // Generate response.
            $response = new \StdClass;
            $response->link = "../../uploads/" . $name;
            echo stripslashes(json_encode($response));
        }
        */
    }
}
