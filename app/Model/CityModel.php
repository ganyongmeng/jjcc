<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\CityModel
 *
 * @mixin \Eloquent
 */
class CityModel extends Model
{
	protected $connection = 'quanyan_place';
    protected $table = "tb_city";
    protected $guarded = [];
    public $timestamps = false;
}