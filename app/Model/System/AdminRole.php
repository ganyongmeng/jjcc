<?php

namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @mixin \Eloquent
 */
class AdminRole extends Model
{
    protected $table = "tb_admin_role";
    protected $guarded = [];
    public $timestamps = false;
}