<?php

namespace App\Logic;

use App\Model\Role;
use App\Model\AdminRole;
use App\Model\RoleMenu;
use App\Model\Menu;
use Illuminate\Support\Facades\DB;

class RoleLogic extends Logic
{
    public function search($data){
        $current_page = isset($data['current_page'])?$data['current_page']:1;
        $query = Role::where('id','>=',0);
        if (!empty($data['name'])){
            $query = $query->where('name','like', '%'.$data['name'].'%');
        }
        $query = $query->orderBy('id', 'desc');
        $fds = ['id','name','desc','create_time','update_time'];
        $res = $query->paginate(PAGE_SIZE,$fds,'',$current_page)->toArray();
        return $res;
    }

    public function option(){
        return Role::select(['id','name'])->orderBy('id','asc')->get();
    }

    public function menu($role_id){
        $res = RoleMenu::where('role_id',$role_id)->pluck('menu_id');
        return $res;
    }

    public function add($data){
        if (Role::create($data)){
            return 200;
        }else{
            return 501;
        }
    }

    public function update($data){
        $id = $data['id'];
        unset($data['id']);
        $data['update_time'] = date('Y-m-d H:i:s');
        if (Role::where('id',$id)->update($data)){
            return 200;
        }else{
            return 502;
        }
    }

    public function setAuth($data){
        DB::beginTransaction();
        try{
            RoleMenu::where('role_id',$data['role_id'])->delete();
            if (!is_null($data['menu_id'])){
                $menu_ids = $data['menu_id'];
                foreach ($menu_ids as $item){
                    RoleMenu::create(['role_id'=>$data['role_id'],'menu_id'=>$item]);
                }
            }
            DB::commit();
            return 200;
        }catch(\Exception $e){
            DB::rollBack();
            info('remove role exception:'.$e->getMessage());
            return 503;
        }
    }

    public function remove($id){
        DB::beginTransaction();
        try{
            Role::destroy($id);
            AdminRole::where('role_id',$id)->delete();
            DB::commit();
            return 200;
        }catch(\Exception $e){
            DB::rollBack();
            info('remove role exception:'.$e->getMessage());
            return 503;
        }
    }

    public function hasOpAuth($menu_flag,$op_type){
        $admin_id = session('login')['id'];
        $role_ids = DB::table('tb_admin_role')->where('admin_id',$admin_id)->pluck('role_id')->toArray();
        $menu_id = Menu::where('flag',$menu_flag)->value('id');
        $fdn = 'u';
        switch($op_type){
            case 'c': $fdn='c'; break;
            case 'u': $fdn='u'; break;
            case 'd': $fdn='d'; break;
        };
        $cnt = RoleMenu::whereIn('role_id',$role_ids)->where('menu_id',$menu_id)->where($fdn,1)->count();
        return ($cnt>0) ? true : false;
    }
}