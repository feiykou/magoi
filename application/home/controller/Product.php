<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/10/12
 * Time: 9:53
 */

namespace app\home\controller;


use think\Controller;

class Product extends Common
{
    public function product(){
        $products = model('product')->getCateProducts();
        $this->assign([
            'productsData'=> $products,
        ]);
        return $this->fetch();
    }
}