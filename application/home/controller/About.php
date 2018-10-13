<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12
 * Time: 9:53
 */

namespace app\home\controller;


use think\Controller;

class About extends Common
{
    public function about(){
        return $this->fetch();
    }
}