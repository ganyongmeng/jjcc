<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\CashDaily
 *
 * @mixin \Eloquent
 */
class CashDaily extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_cash_register_report_day";
    protected $guarded = [];
    public $timestamps = false;
}