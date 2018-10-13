<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\admin\model;


use think\Model;

class User extends Model
{
    // 用户登陆逻辑
    public function login($data){
        $sdata = [
            'name'   => $data['name'],
            'status' => 1
        ];
        $user = $this->where($sdata)->find();
        if($user){
            if($user['pwd'] == md5($data['pwd'])){
                $user = $user->toArray();
                session('user_name',$user['name']);
                session('user_id',$user['id']);
                $this->update([
                    "id" => $user['id']
                ]);
                return 2; // 登录成功的状态
            }else{
                return 3; // 登录密码错误
            }
        }else{
            return 1; // 用户名不存在的情况
        }
    }

    // 通过id获取当前数据
    public function getUserById($id=0){
        $data = [
            'id' => $id
        ];
        return $this->where($data)
            ->find();
    }

    // 获取当前所有数据
    public function getUserAllData(){
        $data = [
            'status'    => ['neq',-1]
        ];
        $order = [
            'id'     => 'desc'
        ];
        $result = $this->where($data)->order($order)
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