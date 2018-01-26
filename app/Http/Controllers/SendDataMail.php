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

    public function checktask(Request $request){
        set_time_limit(0);
        $token = $request->header('token');
        if(empty($token) || $token != '36625475e1a35f5cc7e4c8d8c76889c8'){
            return 'token无效';
        }
        $data = $request->all();
        $date = empty($data['date'])?date('Y-m-d ',strtotime('-3 day')):$data['date'];
        if(!empty($date)){
            $check_date = strtotime($date);
            if(!$check_date){
                return '日期不合法！';
            }
        }
        $i = 0;
        $msg = $date."数据：财务系统数据有异常，请及时处理。异常信息:";
        $pay = $this->logic->checkpay($data);
        $refund = $this->logic->checkrefund($data);
        if(empty($pay)){
            $i++;
            $msg .= "【总-支付】当天无付款数据，请检查是否异常！";
        }
        if(empty($refund)){
            $i++;
            $msg .= "【总-退款】当天无退款数据，请检查是否异常！";
        }

        if(isset($pay['check_pay']) && $pay['check_pay'] != 0){
            $i++;
            $check_pay = empty($pay['check_pay'])?0:$pay['check_pay'];
            $msg .=  '【总-支付】支付账户总额与订单总额差（流水-支付中心订单记录）：'.round($check_pay,2).';';
        }
        if(isset($pay['check_pay_detail']) && $pay['check_pay_detail'] != 0){
            $i++;
            $check_pay_detail = empty($pay['check_pay_detail'])?0:$pay['check_pay_detail'];
            $msg .=  '【场馆-支付】场馆明细与支付中心总额差（场馆明细汇总-支付中心订单记录汇总）：'.round($check_pay_detail,2).';';
        }

        if(isset($refund['check_refund']) && $refund['check_refund'] != 0){
            $i++;
            $check_refund = empty($refund['check_refund'])?0:$refund['check_refund'];
            $msg .=  '【总-退款】支付账户总额与订单退款总额差（流水-支付中心退款订单记录）：'.round($check_refund,2).';';
        }
        if(isset($refund['check_refund_detail']) && $refund['check_refund_detail'] != 0){
            $i++;
            $check_refund_detail = empty($refund['check_refund_detail'])?0:$refund['check_refund_detail'];
            $msg .=  '【场馆-退款】场馆明细与支付中心总额差（场馆订单明细-支付中心订单记录）：'.round($check_refund_detail,2).';';
        }
        if(isset($check_pay) && isset($check_refund)){
            $check_total = $check_pay - $check_refund;
            if($check_total >= 0){
                $i++;
                $msg .=  '【总-支付与退款】支付差异总额与退款差异总额差：'.round($check_total,2).';';
            }
        }

        //俱乐部
        $club_pay = $this->logic->checkClubPay($data);
        $club_refund = $this->logic->checkClubRefund($data);
        if(empty($club_pay) || empty($club_refund)){
            $i++;
            $msg .='【俱乐部】当天无数据，请检查是否异常！';
        }
        $club_pay_check = empty($club_pay['check_pay_detail'])?0:$club_pay['check_pay_detail'];
        if($club_pay_check != 0){
            $i++;
            $msg .= '【俱乐部-支付】俱乐部明细与支付中心总额差（俱乐部明细-支付中心订单记录）：'.round($club_pay_check,2).';';
        }
        $club_refund_check = empty($club_refund['check_refund_detail'])?0:$club_refund['check_refund_detail'];
        if($club_refund_check != 0){
            $i++;
            $msg .= '【俱乐部-退款】俱乐部明细与支付中心总额差（俱乐部明细-支付中心记录）：'.round($club_refund_check,2).';';
        }

        //发送邮件
        $data['emails'] = ['control@quncaotech.com','lei.w@quncaotech.com','yue.yuan@quncaotech.com','hao.cao@quncaotech.com'];
//        $data['emails'] = ['yongmeng.gan@quncaotech.com'];
        $data['msg'] = $msg;
        $data['title'] = "财务系统数据通知";
        if($i <= 0){
            $data['msg'] = $date."：数据无异常！";
        }
        $data['msg'] = '【环境：'.config('app.env').'】'.$data['msg'];
        $this->doSendEmailTask($data);

        Log::info('--------------【'.date('Y-m-d H:i:s',time()).'】财务预警系统日志记录start------------------------------');
        Log::info($data['msg']);
        Log::info('--------------财务预警系统日志记录end--------------------------------');
        
        return 'succcess';
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