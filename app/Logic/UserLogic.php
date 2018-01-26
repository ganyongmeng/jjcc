<?php

namespace App\Logic;

use App\Model\Admin;
use App\Model\AdminRole;
use Illuminate\Support\Facades\DB;

class UserLogic extends Logic
{
    /**
     * 查询管理员用户
     *
     * @param array $data 账号
     * @return array  查询结果
     */
    public function search($data){
        $current_page = isset($data['current_page'])?$data['current_page']:1;
        $query = Admin::where('is_delete',0);
        if (!empty($data['account'])){
            $query = $query->where('account','like', '%'.$data['account'].'%');
        }
        if (!empty($data['name'])){
            $query = $query->where('name','like', '%'.$data['name'].'%');
        }
        if (!empty($data['mobile'])){
            $query = $query->where('mobile','like', '%'.$data['mobile'].'%');
        }
        $query = $query->orderBy('id', 'desc');
        $fields = ['id','account','name','mobile','status','last_login_time','last_login_ip'];
        $res = $query->paginate(PAGE_SIZE,$fields,'',$current_page)->toArray();
        foreach($res['data'] as &$row){//获取账号拥有的角色
            $r = DB::table('tb_admin_role as a')
                ->leftJoin('tb_role as b', 'a.role_id', '=', 'b.id')
                ->where('a.admin_id',$row['id'])
                ->groupBy('a.admin_id')
                ->select([DB::raw('GROUP_CONCAT(b.`name`) as role'),DB::raw('GROUP_CONCAT(b.`id`) as role_ids')])
                ->first();
            if ($r){
                $row['role'] = $r->role;
                $row['auths'] = explode(',',$r->role_ids);
                $row['auths'] = array_map(function($v){return intval($v);},$row['auths']);
            }else{
                $row['role'] = '';
                $row['auths'] = [];
            }
            
        }
        return $res;
    }

    public function add($data){
        DB::beginTransaction();
        $count = Admin::where('account',$data['account'])->count();
        if ($count>0){
            return 1011;
        }
        try{
            $roles = $data['auths'];
            $data['passwd'] = md5(PASSWD_KEY.$data['passwd']);
            unset($data['auths']);
            $admin = Admin::create($data);
            if (!is_null($roles)){
                foreach ($roles as $role_id){
                    AdminRole::create(['role_id'=>$role_id,'admin_id'=>$admin->id]);
                }
            }
            DB::commit();
            return 200;
        }catch(\Exception $e){
            DB::rollBack();
            info('add admin user exception:'.$e->getMessage());
            return 503;
        }
    }

    public function update($data){
        DB::beginTransaction();
        $count = Admin::where('account',$data['account'])->where('id','!=',$data['id'])->count();
        if ($count>0){
            return 1011;
        }
        try{
            $id = $data['id'];
            $roles = $data['auths'];
            unset($data['id']);
            unset($data['auths']);
            $admin = Admin::where('id',$id)->update($data);
            AdminRole::where('admin_id',$id)->delete();
            if (!is_null($roles)){
                foreach ($roles as $role_id){
                    AdminRole::create(['role_id'=>$role_id,'admin_id'=>$id]);
                }
            }
            DB::commit();
            return 200;
        }catch(\Exception $e){
            DB::rollBack();
            info('update admin user exception:'.$e->getMessage());
            return 502;
        }
    }

    public function remove($id){
        DB::beginTransaction();
        try{
            Admin::destroy($id);
            AdminRole::where('admin_id',$id)->delete();
            DB::commit();
            return 200;
        }catch(\Exception $e){
            DB::rollBack();
            info('remove admin user exception:'.$e->getMessage());
            return 503;
        }
    }

    public function changepwd($old_passwd,$new_passwd){
        $id = session('login')['id'];
        $admin = Admin::find($id);
        if (md5(PASSWD_KEY.$old_passwd)===$admin->passwd){
            $admin->passwd = md5(PASSWD_KEY.$new_passwd);
            $admin->save();
            return 200;
        }else{
            return 1013;
        }
    }

    public function resetpwd($id){
        $admin = Admin::find($id);
        $admin->passwd = md5(PASSWD_KEY . RESET_PASSWD);
        $admin->update_time = date('Y-m-d H:i:s');
        $admin->save();
        return 200;
    }
}