<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/13 0013
 * Time: 下午 19:54
 */

namespace app\home\controller;


use think\Controller;

class Common extends Controller
{

    public function _initialize()
    {
        // 获取全部栏目数据
        $this->getNavCates();
        // 获取当前栏目id
        $this->getCateId();

    }

    // 获取全部栏目数据
    public function getNavCates(){
        $columnData = model('category')->getAllColumn();
        $this->assign([
            'columnData'    => $columnData
        ]);
    }
    // 获取当前栏目id
    public function getCateId(){
        $cate_id = input('param.cate_id');
        if($cate_id){
            $result = db("category")->where('id','=',$cate_id)->find();
            if($result){
                $parent_id = $result['parent_id'];
            }else{
                $parent_id = 0;
            }
            $this->assign([
                'cur_cate_id'    => $cate_id,
                'parent_id'      => $parent_id
            ]);
        }
    }


    // 获取友情链接
    public function getFrientLink(){
        $order = [
            'listorder' => 'desc'
        ];
        $result = db("frient_link")->order($order)->select();
        $this->assign('frient_link',$result);
    }

}