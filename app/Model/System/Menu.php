<?php

namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Menu
 *
 * @mixin \Eloquent
 */
class Menu extends Model
{
    protected $table = "tb_menu";
    protected $guarded = [];
    public $timestamps = false;
}