<?php

namespace App\Http\Controllers;

use App\Jobs\SendFinanceMail;
use App\Mail\Taskmail;
use Illuminate\Http\Request;
use App\Logic\CheckDataLogic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;


class QueueTest extends Controller
{
    private $logic;
    public function __construct(CheckDataLogic $logic){
        $this->logic = $logic;
    }

    /**
     *
     */
    public function index(){
        $data['emails'] = ['yongmeng.gan@quncaotech.com'];
        $data['title'] = "test";
        $data['msg']="test";
        $job = new SendFinanceMail($data);
        $job->onConnection("database")->onQueue('SendFinanceMail');
        $res = dispatch($job);
        print_r($res);exit;
    }





}