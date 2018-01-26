<?php

namespace App\Http\Controllers;

use App\Logic\LoginLogic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;

class Login extends Controller
{
    private $logic;
    public function __construct(LoginLogic $logic){
        $this->logic = $logic;
    }

    public function index(){
        if (session('login')){
            return redirect('/admin');
        }
        return view('login/index');
    }

    public function doLogin(Request $req){
        $data = $req->all();
        if (session('login_captcha')!==$data['login_captcha']){
            return $this->response(1004);
        }
        session(['login_captcha'=>null]);
        $code = $this->logic->handle($data['login_account'],$data['login_passwd']);
        return $this->response($code);
    }

    public function getcaptcha(){
        $phraseBuilder = new PhraseBuilder();
        $phrase = $phraseBuilder->build(4,'123456789');
        $captcha = new CaptchaBuilder($phrase);
        $captcha->setBackgroundColor(249,250,252);
        $captcha->build($width = 100, $height = 38, $font = null);
        //获取验证码的内容
        $phrase = $captcha->getPhrase();
        //把内容存入session
        session(['login_captcha'=>$phrase]);
        header("Cache-Control: no-cache, must-revalidate");
        header('Content-Type: image/jpeg');
        return $captcha->output();
    }

    public function out(Request $req){
        session(['login'=>null]);
        return $this->response(200);
    }
}