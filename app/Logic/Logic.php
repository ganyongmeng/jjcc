<?php
/**
 * 逻辑处理基类
 */
namespace App\Logic;

use Illuminate\Support\Facades\DB;

class Logic
{
    public static function getCitys(){
        $citys = DB::connection('quanyan_place')->table('tb_city')
            ->where("is_active", 1)
            ->select(['id','name'])
            ->get()->keyBy('id');
        return $citys;
    }

    public static function getCitysAll(){
        $citys = DB::connection('quanyan_place')->table('tb_city')
            ->select(['id','name'])
            ->orderBy('priority', 'desc')
            ->get()->keyBy('id');
        return $citys;
    }
}