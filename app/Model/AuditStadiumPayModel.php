<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\CityModel
 *
 * @mixin \Eloquent
 */
class AuditStadiumPayModel extends Model
{
    protected $table = "audit_stadium_pay";
    protected $guarded = [];
    public $timestamps = false;

    public function order ()
    {
    	return $this->hasOne('App\StadiumOrderPayModel', 'audit_id');
    }
}