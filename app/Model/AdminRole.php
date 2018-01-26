<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Admin
 *
 * @mixin \Eloquent
 */
class AdminRole extends Model
{
    protected $table = "tb_admin_role";
    protected $guarded = [];
    public $timestamps = false;
}