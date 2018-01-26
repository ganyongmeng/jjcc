<?php

namespace App\Model\Budget;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\Budget\PlModel
 *
 */
class AdjustmentModel extends Model
{
    protected $table = "budget_adjustment_memo";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public static function makeId($departmentId, $year, $month, $categoryId) {
        return md5($departmentId . '_' . $year . '_' . $month . '_' . $categoryId);
    }
}