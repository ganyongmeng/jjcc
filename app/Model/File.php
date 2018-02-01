<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @mixin \Eloquent
 */
class File extends Model
{
    protected $table = "jjcc_file";
    protected $guarded = [];
    public $timestamps = false;
}