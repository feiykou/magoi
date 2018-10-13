<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\admin\controller;


use app\admin\validate\User as UserValidate;

class User extends Base
{

    private $model;
    public function _initialize()
    {
        $this->model = model("User");
        parent::_initialize();

    }

    // 获取分类列表和子类列表
    public function index(){
        $users = $this->model->getUserAllData();
        return $this->fetch('',[
            'users' => $users
        ]);
    }

    // 请求add页面
    public function add(){
        return $this->fetch();
    }

    // 添加和更新数据
    public function save(){
        if(!request()->isPost()){
            $this->error("请求失败");
        }
        $validate = (new UserValidate())->goCheck('add');
        if(!$validate['type']){
            $this->result("",'0',implode('',$validate['msg']));
        }
        // 获取请求数据
        $data = input('post.','','htmlentities');
        $data['pwd'] = md5($data['pwd']);
        $is_exist_id = empty($data['id']);
        //判断是否存在同名
        $is_unique = $this->model->is_unique($data['name'],$is_exist_id?0:$data['id']);
        if($is_unique){
            $this->result('','0','存在同名类');
        }

        // 更新数据
        if(!$is_exist_id){
            return $this->update($data);
        }
        // 添加数据
        $result = $this->model->save($data);
        if($result){
            $this->result(url('index'),'1','添加成功');
        }else{
            $this->result("",'0','添加失败');
        }

    }

    // 编辑
    public function edit($id=0){
        if(intval($id) < 1){
            $this->error("参数不合法");
        }
        $userData = $this->model->getUserById($id);
        return $this->fetch('',[
            'userData' => $userData
        ]);
    }

    // 更新
    public function update($data){
        $result = $this->model->save($data,['id' => intval($data['id'])]);
        if($result){
            $this->result(url('index'),'1','更新成功');
        }else{
            $this->result("",'0','更新失败');
        }
    }

    // 排序
    public function listorder($id,$listorder){
        $result = $this->model->save(['listorder'=>$listorder],['id'=>$id]);
        if($result){
            $this->result($_SERVER['HTTP_REFERER'], 1, '更新完成');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '更新失败');
        }
    }

   public function del($id){
       if(intval($id) < 1){
           $this->error("参数不合法");
       }
       $result = $this->model->where('id','=',$id)->update([
           'status' => -1
       ]);
       if($result){
           $this->result(url('index'),'1','删除成功');
       }else{
           $this->result("",'0','删除失败');
       }
   }

}