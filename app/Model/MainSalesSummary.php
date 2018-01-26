<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\MainSalesSummary
 *
 * @mixin \Eloquent
 */
class MainSalesSummary extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_main_sale_summary_table_day";
    protected $guarded = [];
    public $timestamps = false;
}