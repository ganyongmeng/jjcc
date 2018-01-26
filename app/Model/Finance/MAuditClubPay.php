<?php

namespace App\Model\Finance;

use Illuminate\Database\Eloquent\Model;

class MAuditClubPay extends Model
{
    protected $table = "audit_club_pay";

    protected $guarded = [];

    public $timestamps = false;

    public function report()
    {
        return $this->belongsTo("App\Model\Finance\MReportClubOrderPay", "report_id");
    }
}