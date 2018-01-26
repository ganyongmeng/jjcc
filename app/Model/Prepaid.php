<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Prepaid
 *
 * @mixin \Eloquent
 */
class Prepaid extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_prepaid_fee_report_day";
    protected $guarded = [];
    public $timestamps = false;
}