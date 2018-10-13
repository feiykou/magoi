<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11 0011
 * Time: 下午 16:58
 */

namespace app\admin\controller;

use app\admin\model\User as UserModel;
use think\captcha\Captcha;
use think\Controller;

class Login extends Controller
{
    public function login(){
        $sessionUser = session('user_id');
        if($sessionUser){
            return $this->redirect('/admin');
        }
        return $this->fetch();
    }

    function setCode(){
        $config = [
            'fontSize' =>  20,
            'length'   =>  4,
            'imageW'   =>  130,
            'imageH'   =>  40
        ];
       $capcha = captcha('',$config);
       return $capcha;
    }


    public function index(){
        $sessionUser = session('user','','admin');
        if($sessionUser){
            return $this->redirect('/');
        }
        return $this->fetch();
    }


    // 登录验证
    public function getLogin(){
        if(request()->isPost()){
//            $this->check(input('code'));
            $user = new UserModel();
            $result = $user->login(input('post.'));
            if($result === 1){
                $this->error('用户不存在！@',url('/login'));
            }
            if($result === 3){
                $this->error('密码错误！',url('/login'));
            }
            if($result === 2){
                $this->success('登录成功',url('/admin'));
            }
        }else{
            // 获取session
            $account = session('user_id');
            if($account){
                return $this->redirect(url('/admin'));
            }
            return $this->fetch();
        }
    }

    // 检查验证码
    public function check($code=''){
        if(!captcha_check($code)){
            $this->error('验证码错误',url('/login'));
        }else{
            return true;
        }
    }

    public function loginOut(){
        session(null);
        $this->redirect(url('/login'));
    }
}