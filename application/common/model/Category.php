<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\common\model;


use think\Model;

class Category extends Model
{
    // 获取顶级分类，如果有$id并且是顶级分类，则排除
    public function getNormalFirstCate($id=0){
        $data = [
            'status'    => 1,
            'parent_id' => 0,
            'id'        => ['neq',$id]
        ];

        $order = [
            'id' => 'desc'
        ];

        return $this->where($data)
            ->order($order)
            ->select();
    }

    public function getColumnCate(){
        $data = [
            'status' => 1
        ];
        $columnData = $this->where($data)->select();
        $columnSortData = sortData($columnData);
        return $columnSortData;
    }

    // 获取当前栏目下的所有子分类
    public function getFirstCate($pid = 0){
        $data = [
            'parent_id' => $pid,
            'status'    => ['neq',-1]
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $result = $this->where($data)
                    ->order($order)
                    ->paginate();
        return $result;
    }

    // 判断是否存在同名
    public function is_unique($name="",$id=0){
        $data = [
            'status'    => ['neq',-1],
            'id'        => ['neq',$id],
            'name'      => $name
        ];
        $result = $this->where($data)->find();
        return $result;
    }


    public function getAllColumn(){
        $data = [
            'status'    =>  1,
            'parent_id' =>  0,
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $column = db("category")->where($data)->order($order)->select();
        foreach ($column as $key => $v){
            $children = db("category")->where([
                    'parent_id'=>$v['id'],
                    'status' => 1
                ])->select();
                if($children){
                    $column[$key]['children'] = $children;
                }else{
                    $column[$key]['children'] = 0;
            }
        }
        return $column;
    }


}