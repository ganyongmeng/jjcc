<?php

namespace App\Logic;

use App\Http\Controllers\News;
use App\Model\NewsModel;
use Illuminate\Support\Facades\DB;

class NewsLogic extends Logic
{
    const STATUS_PUBLISHED = 'published';
    const STATUS_DELETED = 'deleted';
    const STATUS_DEFAULT = 'default';

    public function add($data){
        $validator = \Validator::make($data, [
            'content' => 'required|max:10000',
            'title' => 'required|max:200',
            'link' => 'required',
            'cover' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()){//验证出现错误
            $errors = $validator->errors();
            return ['code'=>303,'msg'=>$errors];
        }

        $count = NewsModel::where('link',$data['link'])->count();
        if ($count>0){
            return ['code'=>502,'msg'=>'该链接已存在！'];
        }

        try{
            DB::beginTransaction();
            $status = $data['status'];
            unset($data['status']);
            $news = NewsModel::create($data);
            NewsLogic::setStatus($news, $status);
            DB::commit();
            return ['code'=>200,'msg'=>''];
        }catch(\Exception $e){
            DB::rollBack();
            info(__CLASS__.' '.__METHOD__.' exception:'.$e->getMessage());
            return ['code'=>502,'msg'=>$e->getMessage()];
        }
    }

    public function update($data){
        $validator = \Validator::make($data, [
            'content' => 'required|max:10000',
            'title' => 'required|max:200',
            'link' => 'required',
            'cover' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()){//验证出现错误
            $errors = $validator->errors();
            return ['code'=>303,'msg'=>$errors];
        }


        $count = NewsModel::where('id','!=',$data['id'])->where('link', $data['link'])->count();
        if ($count>0){
            return ['code'=>502,'msg'=>'该链接已存在！'];
        }

        $id = $data['id'];
        try{
            DB::beginTransaction();

            $updateData = $data;
            unset($updateData['id']); //把严禁update的字段都unset掉
            unset($updateData['status']);
            NewsModel::where('id',$id)->update($updateData);
            NewsLogic::setStatus($id, $data['status']);
            DB::commit();
            return ['code'=>200,'msg'=>''];
        }catch(\Exception $e){
            DB::rollBack();
            info(__CLASS__.' '.__METHOD__.' exception:'.$e->getMessage());
            return ['code'=>502,'msg'=>$e->getMessage()];
        }
    }

    public function setOrder($id, $order){
        $validator = \Validator::make(['order'=>$order], [
            'order' => 'required|int',
        ]);
        if ($validator->fails()){//验证出现错误
            $errors = $validator->errors();
            return ['code'=>303,'msg'=>$errors];
        }
        try{
            $news = NewsModel::find($id);
            $news->order = $order;
            $news->save();
            return ['code'=>200,'msg'=>''];
        }catch(\Exception $e){
            info(__CLASS__.' '.__METHOD__.' exception:'.$e->getMessage());
            return ['code'=>502,'msg'=>$e->getMessage()];
        }
    }

    public function setStatus($id, $status){
        $avaiList = [NewsLogic::STATUS_DEFAULT, NewsLogic::STATUS_DELETED, NewsLogic::STATUS_PUBLISHED];
        if (!in_array($status, $avaiList)) {
            return ['code'=>303,'msg'=>'非法状态，设置失败'];
        }
        try{
            $news = is_object($id) ? $id : NewsModel::find($id);
            if ($news->status != $status) {
                if ($status == NewsLogic::STATUS_PUBLISHED) {
                    //每次重新发布，都设置当前时间为发布时间。（这个需求是假定的，随时可改）
                    $news->pub_time = time();
                }
                $news->status = $status;
                $news->save();
            }
            return ['code'=>200,'msg'=>''];
        }catch(\Exception $e){
            info(__CLASS__.' '.__METHOD__.' exception:'.$e->getMessage());
            return ['code'=>502,'msg'=>$e->getMessage()];
        }
    }

    public function search($data){
        $current_page = isset($data['current_page'])?$data['current_page']:1;
        $query = NewsModel::query();
        if (!empty($data['title'])){
            $query = $query->where('title','like', '%'.$data['title'].'%');
        }
        if (!empty($data['type'])){
            $query = $query->where('type', $data['type']);
        }
        $query = $query->orderBy('order', 'asc')->orderBy('pub_time', 'desc');
        $fields = ['id','title','type','pub_time', 'status', 'order'];
        $res = $query->paginate(PAGE_SIZE,$fields,'',$current_page)->toArray();
        return $res;
    }
}