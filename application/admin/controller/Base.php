<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 20:06
 */

namespace app\admin\controller;


use think\Controller;

class Base extends Controller
{
    private $account;
//    public function _initialize()
//    {
//        $isLogin = $this->isLogin();
//        if(!$isLogin){
//            $this->redirect(url('/login'));
//        }
//    }
//
//    /* 判断是否登录 */
//    public function isLogin(){
//        // 获取session
//        $user = $this->getLoginUser();
//        if($user && $user['id']){
//            return true;
//        }
//        return false;
//    }
//
//    /* 获取session数据 */
//    public function getLoginUser(){
//        if(!$this->account){
//            $this->account['id'] = session('user_id');
//        }
//        return $this->account;
//    }

    public function status(){
        // 获取值
        $data = input('param.');
        // 利用tp5 validate 去做严格检验
        if(empty($data['id'])){
            $this->error("id不合法");
        }
        if(!is_numeric($data['status'])){
            $this->error("status不合法");
        }

        // 获取控制器
        $model = request()->controller();
        $result = model($model)->save([
            'status' => $data['status']
        ],['id'=>$data['id']]);


        if($result){
            $this->result('','1','更新成功');
        }else{
            $this->result('','1','更新失败');
        }
    }
//

    public function getImg(){
        if($_FILES['file']['tmp_name']){
            $file = request()->file('file');
            $info = $file->move('upload');
            if($info){
                $img_url = DS . 'upload' . DS . $info->getSaveName();
            }
        }
        if(!empty($img_url)){
            return $this->result($img_url,'1','上传成功','json');
        }else{
            return $this->result('','2','上传失败','json');
        }
    }
//
//    public function getFile(){
//        if($_FILES['file']['tmp_name']){
//            $file = request()->file('file');
//            $info = $file->move('file');
//            if($info){
//                $img_url = DS . 'file' . DS . $info->getSaveName();
//            }
//        }
//        if(!empty($img_url)){
//            return $this->result($img_url,'1','上传成功','json');
//        }else{
//            return $this->result('','2','上传失败','json');
//        }
//    }
}