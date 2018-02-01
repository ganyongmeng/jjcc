<?php

namespace App\Http\Controllers;

use App\Jobs\SendFinanceMail;
use App\Mail\Taskmail;
use Illuminate\Http\Request;
use App\Logic\CheckDataLogic;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Mockery\Exception;

class SendDataMail extends Controller
{
    private $logic;
    public function __construct(CheckDataLogic $logic){
        $this->logic = $logic;
    }



    /**
     * 发送邮件
     * @param $data
     * @return mixed
     */
    private function doSendEmailTask($data){
        $job = new SendFinanceMail($data);
        $job->onConnection("database")->onQueue('SendFinanceMail');
        return dispatch($job);
//        Mail::send('mailtemp.taskmail', $data, function($message) use($data) {
//            $message->to($data['emails'])->subject($data['title']);
//        });

    }





}