<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class Home extends Controller
{
    private $logic;
    public function __construct(){
    }

    public function index(){
        $tempData = [
            'title' => '首页',
            'active_menu_flag' => '',
        ];
        return view('home/index',$tempData);
    }

    public function home(){
        $tempData = [
            'title' => '首页',
            'active_menu_flag' => '',
        ];
        return view('home/home',$tempData);
    }

}