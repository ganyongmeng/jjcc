<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Menu
 *
 * @mixin \Eloquent
 */
class Paywater extends Model
{
    protected $table = "tb_account_pay_water";
    protected $connection = 'quanyan_pay_center';
    protected $guarded = [];

    public $timestamps = false;
}