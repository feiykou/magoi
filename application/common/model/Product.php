<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8 0008
 * Time: 下午 20:22
 */

namespace app\common\model;


use think\Model;

class Product extends Model
{
    protected $autoWriteTimestamp = 'datetime';

    protected $hidden = [
        'delete_time','create_time','update_time'
    ];

    public function procate(){
        return $this->hasMany('procate','id','pro_cate_id')->field('name');
    }

    public function category(){
        return $this->belongsTo('category','column_id','id')->field('name');
    }

    public function getAllProData($data){
        $data['status'] = ['neq',-1];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc',
        ];
        $result = self::where($data)
            ->order($order)
            ->select();
//        echo $this->getLastSql();
        return $result;
    }

    public function getProData($id=0){
        $data = [
            'id'     => $id
        ];
        $proData = self::where($data)->find();
        return $proData;
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
    // 删除元素
    public function delSelChild($idArr=[]){
        $data = [
            'id' => ['in',$idArr]
        ];
        $result = $this->where($data)->update(['status'=>-1]);
        return $result;
    }



    /**
     * 前台数据调用
     */

    // 获取首页推荐新品数据
    public function getHomeMainPro(){
        $data = [
            'status' => 1,
            '' => [
                'exp',"find_in_set('1',attributes)",
            ],[
                'exp',"find_in_set('2',attributes)",
            ]
        ];
        $result = db('product')->where($data)->find();
        return $result;
    }
    // 获取首页数据 --- 使用模型是为了方便提供循环的方式动态的更改键值
    public function getHomePro(){
        $data = [
            'status' => 1,
        ];

        $order = [
            'listorder' => "desc",
            'id'        => "desc"
        ];

        $result = $this->where(function($query){
            $query->where([ '' => ['exp',"find_in_set('1',attributes)"]])
                ->whereor([ '' => ['exp',"find_in_set('2',attributes)"]]);
        })->where($data)->order($order)->select();


//        $result = $this->whereOr()->whereOr([
//            '' => [
//                'exp',"find_in_set('2',attributes)",
//            ]
//        ])->where($data)->order($order)->select();
        return $result;
    }

    // 获取产品页推荐产品数据   attributes=2表示新品推荐
    public function getProMainData($cate_id=0){
        $data = [
            'status' => 1,
            '' => [
                'exp',"find_in_set('2',attributes)"
            ],
            'column_id'  => $cate_id
        ];
        $result = db('product')->where($data)->select();
        return $result;
    }

    // 获取产品页推荐产品数据   attributes=2表示新品推荐
    public function getProDatas($cate_id=0){
        $data = [
            'status'     => 1,
            'attributes' => '',
            'column_id'  => $cate_id
        ];
        $result = db('product')->where($data)->select();
        return $result;
    }

    // 提供栏目id获取某个产品
    public function getProByColumn($cate_id){
        $data = [
            'status'    => 1,
            'column_id' => $cate_id
        ];
        $result =  db('product')->where($data)->find();
        return $result;
    }
    // 获取所有status=1的产品
    public function getProsData(){
        $data = [
            'status'     => 1,
        ];
        $result = db('product')->where($data)->select();
        return $result;
    }


    public function getCate(){
        $data = [
            'status' => 1,
        ];

        $order = [
            'listorder' => "desc",
            'id'        => "desc"
        ];
        $result = db('procate')->where($data)->order($order)->select();
        var_dump($result);
        return $result;
    }


    // 获取分类下的产品
    public function getCateProducts(){
        $data = [
            'status' => 1,
        ];
        $order = [
            'c.listorder' => "desc",
            'c.id'        => "desc"
        ];
        $result = db('procate')->alias('c')
            ->order($order)
            ->field('c.name as cateName,p.*')
            ->join('product p',['c.id = p.pro_cate_id','p.status = 1'])
            ->order('p.listorder','desc')
            ->select();

        $productCateArr = [];
        if(!empty($result)){
            foreach ($result as $val){
                $productCateArr[$val['cateName']][] = $val;
            }
        }

        return $productCateArr;
    }


}