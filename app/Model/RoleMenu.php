<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\RoleMenu
 *
 * @mixin \Eloquent
 */
class RoleMenu extends Model
{
    protected $table = "tb_role_menu";
    protected $guarded = [];
    public $timestamps = false;
}