<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12
 * Time: 9:53
 */

namespace app\home\controller;


use think\Controller;

class Contact extends Common
{
    public function contact(){
        return $this->fetch();
    }
}