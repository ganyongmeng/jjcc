<?php

namespace App\Http\ViewComposers;

use App\Libs\CommonUtils;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Logic\MenuLogic;

class MenuComposer {
    private $logic;
    public function __construct(MenuLogic $logic){
        $this->logic = $logic;
    }

    public function compose(View $view) {
        $admin_id = session('login')['id'];
        $menu = $this->logic->listByAdmin($admin_id);
        $view->with(['menu' => $menu]);//填充数据

    }
}