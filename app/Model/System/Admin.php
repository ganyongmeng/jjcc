<?php

namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @mixin \Eloquent
 */
class Admin extends Model
{
    protected $table = "tb_admin_user";
    protected $guarded = [];
    public $timestamps = false;
}