<?php

namespace App\Model\Budget;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Model\Budget\CategoryModel
 *
 */
class CategoryModel extends Model
{
    protected $table = "budget_category";
    protected $guarded = [];
    public $timestamps = false;

    public $incrementing = false;

}