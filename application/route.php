<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::get('/home','home/index/index');
Route::get('/product/[:cate_id]','home/product/product',[],['cate_id'=>'\d+']);
Route::get('/about/[:cate_id]','home/about/about',[],['cate_id'=>'\d+']);
Route::get('/contact/[:cate_id]','home/contact/contact',[],['cate_id'=>'\d+']);
