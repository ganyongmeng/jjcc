<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Admin
 *
 * @mixin \Eloquent
 */
class PlaceOrder extends Model
{
    protected $connection = 'quanyan_place';
    protected $table = "tb_biz_place_order";
    protected $guarded = [];
    public $timestamps = false;
}