<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\ReceiptOuter
 *
 * @mixin \Eloquent
 */
class ReceiptOuter extends Model
{
    protected $connection = 'quanyan_stat_fact';
    protected $table = "ftbl_receipt_detail_table_day";
    protected $guarded = [];
    public $timestamps = false;
}