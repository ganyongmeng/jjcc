<?php

namespace App\Model\Budget;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\Budget\FactModel
 *
 */
class FactModel extends Model
{
    protected $table = "budget_fact";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public static function makeId($departmentId, $year, $month, $categoryId) {
        return md5($departmentId . '_' . $year . '_' . $month . '_' . $categoryId);
    }
}