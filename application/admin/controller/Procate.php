<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4 0004
 * Time: 下午 16:51
 */

namespace app\admin\controller;

use app\admin\validate\procate as procateValidate;

class Procate extends Base
{

    private $model;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model("procate");
    }

    public function index(){
        $categorys = $this->model->getAllCateData();
        $page = $categorys->render();
        $sortArr = sortData($categorys);
        return $this->fetch('',[
            'categorys' => $sortArr,
            'page'      => $page
        ]);
    }

    public function add(){
        $categorys = $this->model->getCateData();
        return $this->fetch('',[
            "categorys" => $categorys
        ]);
    }

    public function edit($id=0){
        if(intval($id) < 1){
            $this->error("参数不合法");
        }

        $categorys = $this->model->getCateData($id);
        $cateCurrentData = $this->model->getCateById($id);
        return $this->fetch('',[
            "categorys"   => $categorys,
            'cateCurrent' => $cateCurrentData
        ]);
    }


    // 添加和更新数据
    public function save(){
        if(!request()->isPost()){
            $this->error("请求失败");
        }
        $validate = (new procateValidate())->goCheck('add');
        if(!$validate['type']){
            $this->result("",'0',implode('',$validate['msg']));
        }
        // 获取请求数据
        $data = input('post.');
        $is_exist_id = empty($data['id']);
        $data['name'] = htmlentities($data['name']);
        //判断是否存在同名
        $is_unique = $this->model->is_unique($data['name'],$is_exist_id?0:$data['id']);
        if($is_unique){
            $this->result('','0','存在同名类');
        }

        // 更新数据s
        if(!$is_exist_id){
            return $update = $this->update($data);
        }
        // 添加数据
        $result = $this->model->save($data);
        if($result){
            $this->result(url('index'),'1','添加成功');
        }else{
            $this->result("",'0','添加失败');
        }

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

    //删除
    public function del($id=-1){
        if(request()->isPost()){
            $id = request()->post()['idsArr'];
            if($id == []){
                $this->error("无选中的数据！");
            }
        }else{
            if(intval($id)<1){
                $this->error("参数不合法");
            }
        }

        // 判断是否有子类
        $is_child = $this->model->is_child($id);
        if($is_child){
            $this->result('', 0, '存在子类，请先删除子类');
        }
        $is_exist_pro = $this->model->is_exist_pro($id);
        if($is_exist_pro){
            $this->result('', 0, '存在产品，请先去产品列表删除相关产品！');
        }

        if(!is_array($id)){
            $id = [$id];
        }
        // 删除
        $result = $this->model->delSelChild($id);
        // 返回状态码
        if($result){
            $this->result($_SERVER['HTTP_REFERER'], 1, '删除完成');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '删除失败');
        }
    }

}