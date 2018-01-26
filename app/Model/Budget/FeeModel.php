<?php

namespace App\Model\Budget;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\Budget\FeeModel
 *
 */
class FeeModel extends Model
{
    protected $table = "budget_fee";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;

    public static function makeId($departmentId, $year, $categoryId) {
        return md5($departmentId . '_' . $year . '_' . $categoryId);
    }
}