<?php

namespace App\Model\System;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @mixin \Eloquent
 */
class Role extends Model
{
    protected $table = "tb_role";
    protected $guarded = [];
    public $timestamps = false;
}