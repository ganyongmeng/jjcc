<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\PrepaidRefund
 *
 * @mixin \Eloquent
 */
class PrepaidRefund extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_order_pay_refund_detail_table_day";
    protected $guarded = [];
    public $timestamps = false;
}