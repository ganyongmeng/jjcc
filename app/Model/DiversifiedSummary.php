<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\DiversifiedSummary
 *
 * @mixin \Eloquent
 */
class DiversifiedSummary extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_multi_sale_summary_table_day";
    protected $guarded = [];
    public $timestamps = false;
}