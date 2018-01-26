<?php

namespace App\Model\Kingdee;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\Kingdee\DepartmentModel
 *
 */
class DepartmentModel extends Model
{
    protected $table = "kingdee_department";
    protected $guarded = [];
    public $timestamps = false;

    public static function simpleLists(){
        $res = DepartmentModel::select(['id','name'])
            ->orderBy('city_id')
            ->get();
        return $res;
    }

    public function setFactImportRecord($year, $month) {
        $factImportRecord = json_decode_arr($this->fact_import_record);
        if(!isset($factImportRecord[$year])) {
            $factImportRecord[$year] = [];
        }
        $factImportRecord[$year][$month] = 1;
        $this->fact_import_record = \GuzzleHttp\json_encode($factImportRecord);
    }

    public function getFactImportRecord($year, $month) {
        $factImportRecord = json_decode_arr($this->fact_import_record, true);
        if(isset($factImportRecord[$year]) && isset($factImportRecord[$year][$month])) {
            return true;
        }
        return false;
    }

    /*
     * 获取金蝶里面所有所属部门id集合（包括自身）
     */
    public function kingdeeSubDepartments() {
        $res = [
            'type' => 0, //指示本部门在金蝶里面是什么类型的实体，1是“部门”，2是“部门分组”。
            'groupIds' => [], //如果自身是金蝶里的一个或多个分组，那么就记录下分组id集合
            'subIds' => [], //金蝶里的子部门id集合（当type=1时，也有可能就是自己一个）
        ];

        //{{{ 先从金蝶部门分组里面找相同名称的，找不到再去部门表里面找 （都是从名称表里面找，非实体表，因为我们只有名称）

        //从部门分组找出该名称分组
        $query = DB::connection('quanyan_kingdee')->table('T_BD_DEPARTGROUP_L')
            ->where('FNAME',$this->name);
        $data = $query->select(['FID'])->get()->toArray();
        if (!empty($data)) {
            //从部门表找出属于这些分组的部门
            $groupIds = array_pluck($data, 'FID');
            $query = DB::connection('quanyan_kingdee')->table('T_BD_DEPARTMENT')
                ->whereIn('FGROUP', $groupIds);
            $data = $query->select(['FDEPTID'])->get()->toArray();
            if (empty($data)) {
                throw new \Exception('该部门分组并没有包含任何部门：'.$this->name);
            }
            $res['type'] = 2;
            $res['groupIds'] = $groupIds;
            foreach($data as $v) $res['subIds'][] = $v->FDEPTID;

        }else { //再直接从部门表找该名称
            $query = DB::connection('quanyan_kingdee')->table('T_BD_DEPARTMENT_L')
                ->where('FNAME',$this->name);
            $data = $query->select(['FDEPTID'])->get()->toArray();

            if (!empty($data)) {
                $res['type'] = 1;
                foreach ($data as $v) $res['subIds'][] = $v->FDEPTID;
            }else {
                throw new \Exception('在金蝶系统里面无法匹配到该部门:'.$this->name);
            }
        }
        //}}}

        return $res;
    }

}