<?php

namespace App\Http\Controllers;

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
        return view('index/index',$tempData);
    }

}