<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/4 0004
 * Time: 下午 16:51
 */

namespace app\admin\controller;

use app\admin\validate\Product as ProductValidate;

class Product extends Base
{
    private $model;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('product');
    }

    public function index(){
        $data = input('get.');
        $sdata = [];
        // 时间条件范围
        if(!empty($data['start_time']) && !empty($data['end_time']) && strtotime($data['end_time']) > strtotime($data['start_time'])){
            $sdata['create_time'] = [
                ['gt',strtotime($data['start_time'])],
                ['lt',strtotime($data['end_time'])+10000]
            ];
        }
        if(!empty($data['pro_cate_id'])){
            $ids = model('procate')->getChildCateIds($data['pro_cate_id']);
            if($ids == ''){
                $ids .= $data['pro_cate_id'];
            }

            $sdata['pro_cate_id'] = [
                'in',$ids
            ];
        }

        if(!empty($data['name'])){
            $sdata['name'] = ['like','%'.$data['name'].'%'];
        }


        $proAllData = $this->model->getAllProData($sdata);
        getUrlArr($proAllData,'main_img_url');
        $cateData = model('procate')->getCateData();
        $attrData = config('attributes.attribute_type');
        $labelsData = config('attributes.labels_type');

        return $this->fetch('',[
            'proAllData'   => $proAllData,
            'attrData'     => $attrData,
            'labelsData'  => $labelsData,
            'cateData'     => $cateData,
            'start_time'   => empty($data['start_time']) ? '' : $data['start_time'],
            'end_time'     => empty($data['end_time']) ? '' : $data['end_time'],
            'name'         => empty($data['name']) ? '' : $data['name'],
            'pro_cate_id'  => empty($data['pro_cate_id']) ? '' : $data['pro_cate_id']
        ]);
    }

    public function add(){
        $cateData = model('procate')->getCateData();
        $columnSortData = model('category')->getColumnCate();
        $attrData = config('attributes.attribute_type');
        $labelsData = config('attributes.labels_type');
        return $this->fetch('',[
            'cateData'       => $cateData,
            'attrData'       => $attrData,
            'labelsData'  => $labelsData,
            'columnSortData' => $columnSortData,
        ]);
    }

    public function save(){
        if(!request()->post()){
            $this->error("请求失败");
        }
        $validate = (new ProductValidate())->goCheck('add');
        if(!$validate['type']){
            $this->result("",'0',implode('',$validate['msg']));
        }
        // 获取请求数据
        $data = input('post.');
        $proData = [
            'name'           =>      $data['name'],
            'introduce'      =>      $data['introduce'],
            'main_img_url'   =>      empty($data['main_img_url'])?'':$data['main_img_url'],
            'imgs_url'       =>      empty($data['imgs_url'])?'':$data['imgs_url'],
            'side_img'       =>      empty($data['side_img'])?'':$data['side_img'],
            'keywords'       =>      $data['keywords'],
            'description'    =>      $data['description'],
            'detailCon'      =>      empty($data['detailCon'])?'':$data['detailCon'],
            'attributes'     =>      keyInArray($data,'attributes')? implode(',',$data['attributes']):'',
            'labelsAttr'     =>      keyInArray($data,'labelsAttr')? implode(',',$data['labelsAttr']):'',
            'pro_cate_id'    =>      $data['pro_cate_id'],
            'click_num'      =>      $data['click_num'],
            'column_id'      =>      $data['column_id'],
            'price'          =>      $data['price'],
            'ratePrice'      =>      $data['ratePrice'],
            'stock'          =>      $data['stock'],
            'link_url'       =>      $data['link_url'],
        ];
        $is_exist_id = empty($data['id']);

        //判断是否存在同名
        $is_unique = $this->model->is_unique($data['name'],$is_exist_id?0:$data['id']);

        if($is_unique){
            $this->result('','0','存在同名类');
        }

        // 更新数据s
        if(!$is_exist_id){
            $proData['id'] = $data['id'];
            return $update = $this->update($proData);
        }

        $result = $this->model->save($proData);
        if($result){
            $this->result(url('index'),'1','添加成功');
        }else{
            $this->result("",'0','添加失败');
        }
    }

    public function edit($id=0){
        if(intval($id) < 1){
            $this->error("参数不合法");
        }
        $attrData = config('attributes.attribute_type');
        $cateData = model('procate')->getCateData();
        $proAllData = $this->model->getProData($id);
        $proAllData['imgs_url'] = explode(';',trim($proAllData['imgs_url'],';'));
        $proAllData['main_img_url'] = explode(';',trim($proAllData['main_img_url'],';'));
        $proAllData['side_img'] = explode(';',trim($proAllData['side_img'],';'));
        $proAllData['attributes'] = explode(',',$proAllData['attributes']);
        $proAllData['labelsAttr'] = explode(',',$proAllData['labelsAttr']);
        $labelsData = config('attributes.labels_type');
        $columnSortData = model('category')->getColumnCate();
        return $this->fetch('',[
            'proAllData' => $proAllData,
            'attrData'   => $attrData,
            'labelsData'  => $labelsData,
            'cateData'   => $cateData,
            'columnSortData' => $columnSortData
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

    // 排序
    public function listorder($id,$listorder){
        $result = $this->model->save(['listorder'=>$listorder],['id'=>$id]);
        if($result){
            $this->result($_SERVER['HTTP_REFERER'], 1, '更新完成');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '更新失败');
        }
    }

}