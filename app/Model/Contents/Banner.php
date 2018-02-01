<?php

namespace App\Model\Contents;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @mixin \Eloquent
 */
class Banner extends Model
{
    protected $table = "jjcc_banner";
    protected $guarded = [];
    public $timestamps = false;
}