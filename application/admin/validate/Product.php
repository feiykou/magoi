<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class Product extends BaseValidate
{
    protected $rule = [
        ['name', 'require', '产品名必须填写'],
        ['main_img_url','require','缩略图必须添加'],
        ['imgs_url','require','产品图必须添加'],
        ['id', 'number'],
        ['status', 'number|in:-1,0,1', '状态必须是数字|状态范围不合法']
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id','name','main_img_url','column_id'],
        'status'      => ['id','status']
    ];
}