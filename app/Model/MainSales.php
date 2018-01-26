<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\MainSales
 *
 * @mixin \Eloquent
 */
class MainSales extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_main_sale_detail_table_day";
    protected $guarded = [];
    public $timestamps = false;
}