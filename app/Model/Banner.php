<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Admin
 *
 * @mixin \Eloquent
 */
class Banner extends Model
{
    protected $table = "jjcc_banner";
    protected $guarded = [];
    public $timestamps = false;
}