<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:14
 */

namespace app\admin\controller;



class Index extends Base
{
    public function Index(){
        return $this->fetch();
    }
    public function welcome(){
        return $this->fetch();
    }
}