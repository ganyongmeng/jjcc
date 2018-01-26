<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\Role
 *
 * @mixin \Eloquent
 */
class Role extends Model
{
    protected $table = "tb_role";
    protected $guarded = [];
    public $timestamps = false;
}