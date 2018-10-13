<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/31 0031
 * Time: 下午 15:46
 */

namespace app\home\controller;


use think\Controller;

class Index extends Common
{
    public function index(){
        return $this->fetch();
    }
}
