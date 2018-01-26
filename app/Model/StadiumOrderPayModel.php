<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Model\CityModel
 *
 * @mixin \Eloquent
 */
class StadiumOrderPayModel extends Model
{
    protected $table = "report_stadium_order_pay";
    protected $guarded = [];
    public $timestamps = false;

    public function audit ()
    {
    	return $this->belongsTo('App\Model\AuditStadiumPayModel', 'audit_id');
    }
}