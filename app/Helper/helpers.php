<?php
/**
 * 公共函数
 * Created by PhpStorm.
 * User: gym
 * Date: 2017/7/26
 * Time: 11:33
 */

/**
 * 支付类型
 * @return array
 */
function payType(){
    return [
        0 => "现场支付",
        1 => "支付宝支付",
        2 => "微信支付",
        3 => "微信公众号支付",
        4 => "银联支付",
        5 => "apple pay",
        6 => "免费",
        7 => "鸟蛋",
        8 => "蛋壳",
        9 => "其他抵扣",
        10 => "会员卡支付",
        11 => "优惠券支付",
        20 => "现金",
        21 => "微信扫码",
        22 => "支付宝扫码",
        23 => "POS银行卡",
        24 => "与场馆老板结算",
        25 => "与外部单位结算",
        26 => "与总部内部核算",
        27 => "POS机微信",
        28 => "POS机支付宝",
        29 => '对公转账',
        30 => '挂账',
        31 => '团购券',
        32 => '支付宝（场馆自有）',
        33 => '微信（场馆自有）',
        34 => 'POS银行卡（场馆自有）',
        35 => '与场馆老板结算（散客）',
        36 => 'POS银行卡（银联）',
        37 => '赠送'
    ];
}

function systype(){
    return [
//        'ACTIVITY_SYSTEM_CODE' => "活动系统", //活动系统
        'ASSETS_SYSTEM_CODE' => "资产系统", //资产系统(虚拟货币)
        'CLUB_SYSTEM_CODE' => "俱乐部系统", //俱乐部系统
//        'TRAVEL_SYSTEM_CODE' => "户外系统", //户外系统
        'STADIUM_SYSTEM_CODE' => "运动场系统", //运动场系统
        'PLACE_SYSTEM_CODE' => "场馆系统", //场馆系统
//        'ORDER_SYSTEM_CODE' => "订单系统", //订单系统
//        'CENTER_SYSTEM_CODE' => "个人中心系统", //个人中心系统
//        'USER_CENTER_SYSTEM_CODE' => "用户中心系统", //用户中心系统
        'AUCTION_SYSTEM_CODE' => "达人系统", //竞拍系统
//        'OPERATIVE_SYSTEM_CODE' => "运营活动系统",//运营活动系统
        'UNKNOWN_SYSTEM_CODE' => "未知",//未知
        'VIP_VENUE_SYSTEM_CODE' => "场馆VIP",//场馆VIP
        'PLACE_SCANPAY_BUSINESS_CODE' => "场馆扫码支付",//场馆扫码支付
    ];
}

function exportToExcel($filename, $tileArray=[], $dataArray=[]){
    ini_set('memory_limit','512M');
    ini_set('max_execution_time',0);
    ob_end_clean();
    ob_start();
    header("Content-Type: text/csv");
    header("Content-Disposition:filename=".$filename);
    $fp=fopen('php://output','w');
    fwrite($fp, chr(0xEF).chr(0xBB).chr(0xBF));
    fputcsv($fp,$tileArray);
    $index = 0;
    foreach ($dataArray as $item) {
        if($index==10000){
            $index=0;
            ob_flush();
            flush();
        }
        $index++;
        fputcsv($fp,$item);
    }
    ob_flush();
    flush();
    ob_end_clean();exit;
}
/**
 * CURL POST提交
 * @param $post_url
 * @param $data
 * @return mixed
 */
function CurlPost($post_url, $data)
{
    //设置文件头
    $header = array(
        'Content-Type: application/json',
        'signkey'=>'quanyan2016'
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $post_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, @$data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
    $contents = curl_exec($ch);
    curl_close($ch);
    return $contents;
}

/*
 * 对数组里面所有元素
 */
function roundForArray(&$arr) {
    foreach($arr as &$v) {
        if (is_numeric($v)) $v=number_format($v, 2);
    }
}

/*
 * 如果检测到时百分比数字，把它转成float
 */
function percentageToNum($oldValue) {
    if(substr($oldValue, -1, 1) == '%') {
        $value = rtrim($oldValue, '%');
        if (is_numeric($value)) {
            return $value / 100;
        }
    }
    return $oldValue;
}

/*
 * 如果检测到数字，把它转成百分比
 */
function numToPercentage($oldValue) {
    if (is_numeric($oldValue) || is_float($oldValue)) {
        $res = number_format((float)$oldValue * 100, 2);
        if (0 == $res) {
            return '0%';
        }else {
            return $res . '%';
        }
    }
    return $oldValue;
}

function json_decode_arr($str)
{
    if (strlen($str) == 0) {
        $res = array();
    } else {
        $res = json_decode($str, true);
    }

    return $res;
}

function big_download($path, $name = null, array $headers = array())
{
    if (is_null($name)) $name = basename($path);

    // Prepare the headers
    $headers = array_merge(array(
        'Content-Description'       => 'File Transfer',
        'Content-Type'              => File::mime(File::extension($path)),
        'Content-Transfer-Encoding' => 'binary',
        'Expires'                   => 0,
        'Cache-Control'             => 'must-revalidate, post-check=0, pre-check=0',
        'Pragma'                    => 'public',
        'Content-Length'            => File::size($path),
    ), $headers);

    $response = new Response('', 200, $headers);
    $response->header('Content-Disposition', $response->disposition($name));

    // If there's a session we should save it now
    if (Config::get('session.driver') !== '')
    {
        Session::save();
    }

    // Below is from http://uk1.php.net/manual/en/function.fpassthru.php comments
    session_write_close();
    ob_end_clean();
    $response->send_headers();
    if ($file = fopen($path, 'rb')) {
        while(!feof($file) and (connection_status()==0)) {
            print(fread($file, 1024*8));
            flush();
        }
        fclose($file);
    }

    // Finish off, like Laravel would
    Event::fire('laravel.done', array($response));
    $response->foundation->finish();

    exit;
}