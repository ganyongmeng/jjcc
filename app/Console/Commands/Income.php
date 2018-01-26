<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Logic\King\HonorLogic;

class Income extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'income:calculate {date1} {date2}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '场馆每日积分计算    {date : 需要计算积分的日期(yyyy-MM-dd)，不传默认前一天}';

    protected $logic;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(HonorLogic $logic)
    {
        parent::__construct();
        $this->logic = $logic;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        info("income:calculate exec.");
        $date1 = $this->argument('date1');
        $date2 = $this->argument('date2');
        $unixTime1 = strtotime($date1);
        $unixTime2 = strtotime($date2);
        if (strlen($date1)!=10 || !$unixTime1) { //strtotime转换不对，日期格式显然不对。
            echo "日期1格式不正确\r\n";
            exit;
        }
        if (strlen($date2)!=10 ||!$unixTime2) { //strtotime转换不对，日期格式显然不对。
            echo "日期2格式不正确\r\n";
            exit;
        }
        if ($unixTime1>$unixTime2){
            echo "开始时间大于结束时间\r\n";
        }
        $time = $unixTime1;
        do{
            $date = date('Y-m-d',$time);
            $this->logic->handle($date);
            echo date('Y-m-d H:i:s')." [{$date}] calculate integral success.\r\n";
            $time = strtotime('+1day',$time);
        }while($time<=$unixTime2);
    }
}
