<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\admin\controller;


use app\admin\validate\Category as CateValidate;

class Category extends Base
{

    private $model;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model("Category");
    }


    // 获取分类列表和子类列表
    public function index(){
        $parentId = input('param.parent_id',0,'intval');
        $categorys = $this->model->getFirstCate($parentId);
        $crumbData = $this->crumb($parentId);
        return $this->fetch('',[
            'categorys' => $categorys,
            'parentId' => $parentId,
            'crumbData' => $crumbData
        ]);
    }

    // 请求add页面
    public function add(){
        $categorys = $this->model->getNormalFirstCate();
        $template_type = config('template.template_type');
        return $this->fetch('',[
            "categorys"     => $categorys,
            "template_type" => $template_type
        ]);
    }

    // 添加和更新数据
    public function save(){
        if(!request()->isPost()){
            $this->error("请求失败");
        }
        $validate = (new CateValidate())->goCheck('add');
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
        $category = $this->model->get($id);
        $categorys = $this->model->getNormalFirstCate($id);
        $template_type = config('template.template_type');
        return $this->fetch('',[
            'categorys'     => $categorys,
            'category'      => $category,
            'template_type' => $template_type
        ]);
    }

    // 更新
    public function update($data){
        $result = $this->model->save($data,['id' => intval($data['id'])]);
        if($result){
            $this->result(url('index'),'1','添加成功');
        }else{
            $this->result("",'0','添加失败');
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

    public function crumb($cur_pid=0){
        $str = [];
        $result = $this->model->where([
            'id' => $cur_pid,
            'status'    => ['neq',-1]
        ])->find();
        if($result){
            $str['name'] = $result['name'];
        }
        return $str;
    }

}