<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\PayType
 *
 * @mixin \Eloquent
 */
class PayType extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_place_payment_sum_day";
    protected $guarded = [];
    public $timestamps = false;
}