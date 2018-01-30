<?php
/**
 * 官网首页banner
 * author:gym
 * date:2018-01-26
 */
namespace App\Logic;

use Illuminate\Support\Facades\DB;

class BannerLogic
{
    const BANNER = "jjcc_banner";
    public function bannerinfo(){
        return DB::table(self::BANNER)->first();
    }

    /**
     * 更新banner
     * @param $data
     * @return mixed
     */
    public function editBanner($data){

        $temp = [
            'b_time'=>empty($data['b_time'])?5:intval($data['b_time']),
            'create_time'=>date('Y-m-d H:i:s',time()),
            'filelist'=>empty($data['filelist'])?'':serialize((array)$data['filelist'])
        ];
        if(!empty($data['is_delete'])){
            unset($temp['b_time']);
            unset($temp['create_time']);
            return DB::table(self::BANNER)->where('id',$data['id'])->update($temp);
        }
        if(empty($data['id'])){
            return DB::table(self::BANNER)->insert($temp);
        }else{
            unset($temp['create_time']);
            return DB::table(self::BANNER)->where('id',$data['id'])->update($temp);
        }
    }



}