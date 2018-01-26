<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\MemberCharge
 *
 * @mixin \Eloquent
 */
class MemberCharge extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_place_member_charge_detail_day";
    protected $guarded = [];
    public $timestamps = false;
}