<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\CityModel
 *
 * @mixin \Eloquent
 */
class CompanyModel extends Model
{
    protected $table = "company_account";
    protected $guarded = [];
    public $timestamps = false;
}