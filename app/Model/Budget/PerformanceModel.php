<?php

namespace App\Model\Budget;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\Budget\PerformanceModel
 *
 */
class PerformanceModel extends Model
{
    protected $table = "budget_performance";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public static function makeId($departmentId, $year, $month, $categoryId) {
        return md5($departmentId . '_' . $year . '_' . $month . '_' . $categoryId);
    }

    public static function findOrCreate($departmentId, $year, $month, $category) {
        $id = self::makeId($departmentId, $year, $month, $category->id);
        $model = self::find($id);
        if (!$model) {
            $model = new self();
            $model->id = $id;
            $model->department_id =  $departmentId;
            $model->year =  $year;
            $model->month =  $month;
            $model->category_id =  $category->id;
            $model->category_name_cache =  $category->name;
        }
        return $model;
    }
}