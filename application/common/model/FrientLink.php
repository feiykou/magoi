<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\common\model;


use think\Model;

class FrientLink extends Model
{
    // 通过id获取当前数据
    public function getFlinkById($id=0){
        $data = [
            'id' => $id
        ];
        return $this->where($data)
            ->find();
    }

    // 获取当前栏目下的所有子分类
    public function getFlinkAllData(){
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $result = $this->order($order)
                    ->paginate();
        return $result;
    }

    // 判断是否存在同名
    public function is_unique($name="",$id=0){
        $data = [
            'id'        => ['neq',$id],
            'name'      => $name
        ];
        $result = $this->where($data)->find();
        return $result;
    }


}