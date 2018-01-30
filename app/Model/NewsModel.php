<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NewsModel extends Model
{
    protected $table = "jjcc_news";
    protected $guarded = [];
    public $incrementing = true;

}