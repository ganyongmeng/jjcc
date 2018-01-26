<?php

/**
 * 完成对单个部门的取数
 */

namespace App\Jobs;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Logic\Budget\FactLogic;
use Mockery\Exception;

class BudgetCalFactByDeptJob extends Job implements ShouldQueue
{
    use InteractsWithQueue;

    protected $departmentId;
    protected $year;
    protected $month;
    //protected $categories;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($year, $month, $departmentId, $categories=null)
    {
        $this->departmentId = $departmentId;
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
        $logic = new FactLogic();
        info('财务预算，批量取数：job start 部门:'.$this->departmentId.' 年月:'.$this->year.'-'.$this->month);
        $res = $logic->batchCalDepartmentFact($this->year, $this->month, [$this->departmentId]);
        info('财务预算，批量取数：job end: '. \GuzzleHttp\json_encode($res));
        if ($res['code']!=200) {
            throw new \Exception($res['msg']);
        }
    }

}
