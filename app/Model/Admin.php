<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Admin
 *
 * @mixin \Eloquent
 */
class Admin extends Model
{
    protected $table = "tb_admin_user";
    protected $guarded = [];
    public $timestamps = false;
}