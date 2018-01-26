<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\SalesCost
 *
 * @mixin \Eloquent
 */
class SalesCost extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_main_sale_sum_table_day";
    protected $guarded = [];
    public $timestamps = false;
}