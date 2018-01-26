<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class Banner extends Controller
{
    private $logic;
    public function __construct(){
    }

    public function index(){
        $tempData = [
            'title' => 'banner管理',
            'active_menu_flag' => '',
        ];
        return view('home/banner/index',$tempData);
    }

}