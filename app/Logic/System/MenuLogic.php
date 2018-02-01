<?php

namespace App\Logic\System;

use App\Logic\Logic;
use App\Model\System\Menu;
use App\Model\System\RoleMenu;
use Illuminate\Support\Facades\DB;

class MenuLogic extends Logic
{
    /**
     * 获取当前管理员的菜单
     *
     * @return void
     */
    public function listByAdmin($admin_id){
        $role_ids = DB::table('tb_admin_role')->where('admin_id',$admin_id)->pluck('role_id')->toArray();
        if (in_array(SUPER_ADMIN_ROLE_ID,$role_ids)){//包含超级管理员角色,获取所有菜单
            $fds = ['id','name','flag','icon','link'];
            $menu = Menu::where('pid',0)
                ->where('is_delete',0)
                ->select($fds)
                ->orderBy('seq','asc')
                ->get()->toArray();
            foreach ($menu as &$item){
                $item['sub_menu'] = Menu::where('pid',$item['id'])
                    ->where('is_delete',0)
                    ->select($fds)
                    ->orderBy('seq','asc')
                    ->get()->toArray();
            }
        }else{
            $menu_id = RoleMenu::whereIn('role_id',$role_ids)->pluck('menu_id')->toArray();
            $fds = ['id','name','flag','icon','link'];
            $menu = Menu::where('pid',0)
                ->where('is_delete',0)
                ->whereIn('id',$menu_id)
                ->select($fds)
                ->orderBy('seq','asc')
                ->get()->toArray();
            foreach ($menu as &$item){
                $item['sub_menu'] = Menu::where('pid',$item['id'])
                    ->where('is_delete',0)
                    ->whereIn('id',$menu_id)
                    ->select($fds)
                    ->orderBy('seq','asc')
                    ->get()->toArray();
            }
        }
        return $menu;
    }

    public function search($data){
        $current_page = isset($data['current_page'])?$data['current_page']:1;
        $query = DB::table('tb_menu as a')
            ->leftJoin('tb_menu as b','a.pid','=','b.id')
            ->where('a.is_delete',0);
        if (!empty($data['name'])){
            $query = $query->where('a.name','like', '%'.$data['name'].'%');
        }
        if (!empty($data['flag'])){
            $query = $query->where('a.flag','like', '%'.$data['flag'].'%');
        }
        if (isset($data['pid']) && $data['pid']!==''){
            $query = $query->where('a.pid',$data['pid']);
        }
        $query->select(['a.id','a.name','a.flag','a.icon','a.link','a.seq','a.pid','b.name as pname']);
        $query = $query->orderBy('a.id', 'desc');
        $res = $query->paginate(PAGE_SIZE,['a.id'],'',$current_page)->toArray();
        return $res;
    }

    public function option(){
        $f = ['id'=>0,'name'=>'顶级菜单'];
        $res = Menu::where('pid',0)->where('is_delete',0)->select(['id','name'])->orderBy('seq','asc')->get()->toArray();
        $res[] = $f;
        sort($res);
        return $res;
    }

    public function tree(){
        $fds = ['id','name as label'];
        $menu = Menu::where('pid',0)
            ->where('is_delete',0)
            ->select($fds)
            ->orderBy('seq','asc')
            ->get()->toArray();
        foreach ($menu as &$item){
            $item['children'] = Menu::where('pid',$item['id'])
            ->where('is_delete',0)
            ->select($fds)
            ->orderBy('seq','asc')->get()->toArray();
        }
        return $menu;
    }

    public function add($data){
        if (Menu::create($data)){
            return 200;
        }else{
            return 501;
        }
    }

    public function update($data){
        $id = $data['id'];
        unset($data['id']);
        $data['update_time'] = date('Y-m-d H:i:s');
        if (Menu::where('id',$id)->update($data)){
            return 200;
        }else{
            return 502;
        }
    }

    public function remove($id){
        if (Menu::destroy($id)){
            return 200;
        }else{
            return 503;
        }
    }
}