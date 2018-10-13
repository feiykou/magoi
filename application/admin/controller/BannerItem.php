<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/28 0028
 * Time: 上午 9:45
 */

namespace app\admin\controller;



use app\admin\validate\BannerItemValidate;
use app\admin\validate\IDMustBePostiveInt;

class BannerItem extends Base
{
    private $model;
    function _initialize()
    {
        parent::_initialize();
        $this->model = model('BannerItem');
    }

    public function index(){
        $types = config('Banner.banner_type');
        $banner_id = input('get.banner_id',1,'intval');
        $redic_types = config('Banner.redictor_type');
        $redic_arr = [];
        foreach ($redic_types as $value){
            $redic_arr[$value['type']] = $value['name'];
        }
        $this->assign([
            'bannerArr' => $types,
            'redic_typeArr' => $redic_arr,
            'banner_id' => $banner_id
        ]);

        $bannerItemsData = $this->model->getBannerByBId($banner_id);

        $this->assign('bannerItemsData',$bannerItemsData);
        return $this->fetch();
    }

    public function add(){
        $types = config('Banner.banner_type');
        $redic_types = config('Banner.redictor_type');

        $this->assign([
            'bannerArr' => $types,
            'redic_typeArr' => $redic_types
        ]);
        return $this->fetch();

    }

    public function edit($id){
//        (new IDMustBePostiveInt())->goCheck();
        $types = config('Banner.banner_type');
        $redic_types = config('Banner.redictor_type');
        $bannerIdData = $this->model->getCurrentBanner($id);
        $this->assign([
            'bannerArr' => $types,
            'redic_typeArr' => $redic_types,
            'bannerIdData' => $bannerIdData
        ]);
        return $this->fetch();
    }

    public function save(){
        if(!request()->post()){
            $this->error("请求失败");
        }

        $data = request()->post();
        $is_exist_id = empty($data['id']);
        //判断是否存在同名
        $is_unique = $this->model->is_unique($data['name'],$is_exist_id?0:$data['id']);
        if($is_unique){
            $this->result('','0','存在同名类');
        }

        // 更新数据
        if(!$is_exist_id){
            return $update = $this->update($data);
        }

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

    /**
     * 文章删除
     */
    function removeArt($id){
        $result = $this->model->removeDataById($id);
        if($result){
            return json(['type'=>'success','success'=>"删除成功！","code"=>0]);
        }else{
            return json(['type'=>'success','error'=>"删除失败！","code"=>0]);
        }
    }

    /**
     * 删除多条文章
     */
    function removeMoreArt(){
        $idArr = input('post.')['idsArr'];
        $result = $this->model->removeMoreDataById($idArr);
        if($result){
            return json(['type'=>'success','success'=>"删除成功！","code"=>0]);
        }else{
            return json(['type'=>'success','error'=>"删除失败！","code"=>0]);
        }
    }
}