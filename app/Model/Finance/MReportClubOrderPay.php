<?php

namespace App\Model\Finance;

use Illuminate\Database\Eloquent\Model;

class MReportClubOrderPay extends Model
{
    protected $table = "report_pay_club_order";

    protected $guarded = [];

    public $timestamps = false;

    public function audits()
    {
        return $this->hasMany("App\Model\Finance\MAuditClubPay", "report_id", "id");
    }

    public function audit()
    {
        return $this->hasOne("App\Model\Finance\MAuditClubPay", "id", "audit_id");
    }
}
