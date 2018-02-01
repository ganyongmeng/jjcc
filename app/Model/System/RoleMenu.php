<?php

namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @mixin \Eloquent
 */
class RoleMenu extends Model
{
    protected $table = "tb_role_menu";
    protected $guarded = [];
    public $timestamps = false;
}