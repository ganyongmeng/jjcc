<?php

/**
 * 完成对单个部门的取数
 */

namespace App\Jobs;

use App\Logic\Budget\PerformanceLogic;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Logic\Budget\FactLogic;
use Mockery\Exception;

class BudgetCalPerformanceByCity extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    const TYPE_CAL = 'TYPE_CAL';  //计算报表
    const TYPE_PL_EXPORT = 'TYPE_PL_EXPORT';  //导出损益excel
    const TYPE_FEE_EXPORT = 'TYPE_FEE_EXPORT';  //导出费用excel
    const TYPE_PL_FINAL = 'TYPE_PL_FINAL';  //损益最终处理
    const TYPE_FEE_FINAL = 'TYPE_FEE_FINAL';  //费用最终处理

    protected $cityId;
    protected $type;
    protected $year;
    protected $month;
    //protected $categories;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($year, $month, $type, $cityId=null)
    {
        $this->cityId = $cityId;
        $this->type = $type;
        $this->year = $year;
        $this->month = $month;
        //$this->categories = $categories;  //以后可以试试传categories进去，节省一点运行资源
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        ini_set("memory_limit","-1");

        if ($this->type == self::TYPE_CAL) {
            $logic = new PerformanceLogic();
            info('财务预算，批量生成报表：job start 城市:'.$this->cityId.' 年月:'.$this->year.'-'.$this->month);
            $res = $logic->calDepartmentPerformanceByCity($this->year, $this->month, $this->cityId);
            info('财务预算，批量生成报表：job end: '. \GuzzleHttp\json_encode($res));
            if ($res['code']!=200) {
                throw new \Exception($res['msg']);
            }
        }elseif($this->type == self::TYPE_PL_EXPORT || $this->type == self::TYPE_FEE_EXPORT) {
            $logic = new PerformanceLogic();
            info('财务预算，批量导出excel：job start 城市:'.$this->cityId.' 年月:'.$this->year.'-'.$this->month);
            $res = $logic->exportPerformance($this->year, $this->month, $this->cityId, $this->type);
            info('财务预算，批量导出excel：job end: '. \GuzzleHttp\json_encode($res));
            if ($res['code']!=200) {
                throw new \Exception($res['msg']);
            }
        }elseif($this->type == self::TYPE_PL_FINAL || $this->type == self::TYPE_FEE_FINAL) {
            $logic = new PerformanceLogic();
            info('财务预算，最终处理excel：job start 年月:'.$this->year.'-'.$this->month);
            $res = $logic->combinePerformanceFile($this->year, $this->month, $this->type);
            info('财务预算，最终处理excel：job end: '. \GuzzleHttp\json_encode($res));
            if ($res['code']!=200) {
                throw new \Exception($res['msg']);
            }
        }
    }
}
