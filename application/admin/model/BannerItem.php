<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/26
 * Time: 17:18
 */

namespace app\admin\model;


use think\Model;

class BannerItem extends Model
{
    protected $autoWriteTimestamp = true;


    public function createBannerItem($data){
        $id = self::insertGetId($data);
        return $id;
    }

    public function getBannerByBId($banner_id){
        $data = [
            "banner_id" => $banner_id
        ];
        $order = [
            'id' => 'desc'
        ];
        $result = self::where($data)->order($order)->select();
        return $result;
    }

    public function getCurrentBanner($id){
        $data = [
            "id" => $id
        ];
        $result = self::where($data)->find();
        return $result;
    }

    /**
     * 删除某一条数据
     * @param int $id
     * @return int
     */
    public function removeDataById($id=0){
        $data = [
            'id' => $id,
        ];

        $result = $this->where($data)->delete();
        return $result;
    }

    /**
     * 删除多条数据
     */
    public function removeMoreDataById($idArr=[]){

        $data = [
            'id' => ['in',$idArr],
        ];
        $result = self::where($data)->delete();
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