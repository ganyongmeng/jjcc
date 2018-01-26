<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Diversified
 *
 * @mixin \Eloquent
 */
class Diversified extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_multi_sale_cost_detail_table_day";
    protected $guarded = [];
    public $timestamps = false;
}