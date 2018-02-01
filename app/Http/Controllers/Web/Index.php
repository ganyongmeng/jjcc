<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class Index extends Controller
{
    private $logic;
    public function __construct(){
    }

    public function index(){
        $tempData = [
            'title' => '首页',
            'active_menu_flag' => '',
        ];
        return view('web/index',$tempData);
    }

    public function home(){
        $tempData = [
            'title' => '首页',
            'active_menu_flag' => '',
        ];
        return view('web/home',$tempData);
    }
    public function test(){
        $tempData = [
            'title' => '首页',
            'active_menu_flag' => '',
        ];
        return view('web/test',$tempData);
    }

}