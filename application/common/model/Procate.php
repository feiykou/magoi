<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 11:03
 */

namespace app\common\model;


use think\Model;

class Procate extends Model
{
    protected $hidden = [
        'create_time','update_time'
    ];
    public function getAllCateData(){
        $cateData = self::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->where(['a1.status'=>1])
            ->order([
                'a1.listorder'=>'desc',
                'a1.id' => 'desc'
            ])
            ->join('procate a2','a1.parent_id=a2.id','left')
            ->paginate();
        return $cateData;
    }

    /**
     * 获取当前id的父级元素和其他元素
     * @param $id
     * @return mixed
     */
    public static function getCateData($id=-1){
        $model = new self();
        $data = [
            'status' => 1
        ];
        $ModelDatas = $model->where($data)->select();

        $ids = "";
        if($id != -1){
            $sonDatas = sortData($ModelDatas,$id);
            foreach ($sonDatas as $key=>$value){
                $ids.= $value['id'].',';
            }
            $ids .= trim($id);
            $parentDept = $model->where(["id"=>['not in',$ids],'status'=>1])->select();
        }else{
            $parentDept = $ModelDatas;
        }
        // 排除id字符串的两种方式
        if(!$parentDept){
            return false;
        }else{
            // 对部门进行重新排序
            $parentSortDept = sortData($parentDept);
        }
        return $parentSortDept;
    }

    public function getChildCateIds($id=-1){
        $data = [
            'status' => 1
        ];
        $ModelDatas = self::where($data)->select();
        $ids = "";
        if($id != -1){
            $sonDatas = sortData($ModelDatas,$id);
            foreach ($sonDatas as $key=>$value){
                $ids.= $value['id'].',';
            }
            $ids .= trim($id);
        }
        return $ids;
    }

    public function getCateById($id=0){
        $data = [
            'status' => 1,
            'id'     => $id
        ];
        $modelDatas = self::where($data)->find();
        return $modelDatas;
    }

    // 判断是否存在同名
    public function is_unique($name="",$id=0){
        $data = [
            'status'    => 1,
            'id'        => ['neq',$id],
            'name'      => $name
        ];
        $result = $this->where($data)->find();
        return $result;
    }

    // 判断当前id是否存在子类
    public function is_child($id=-1){
        if(is_numeric($id)){
            $id = [$id];
        }
        $data = [
            "parent_id"  => ['in',$id],
            'status' => 1
        ];
        $result = self::where($data)->find();

        return $result;
    }

    public function delSelChild($idArr=[]){
        $data = [
            'id' => ['in',$idArr]
        ];
        $result = $this->where($data)->update(['status'=>0]);
        return $result;
    }

    public function is_exist_pro($id){
        if(is_numeric($id)){
            $id = [$id];
        }
        $data = [
            'status'      => ['neq',-1],
            'pro_cate_id' => ['in',$id]
        ];
        $result = \model('product')->where($data)->find();
        return $result;
    }


}