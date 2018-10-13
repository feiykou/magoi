<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class Solution extends BaseValidate
{
    protected $rule = [
        ['name', 'require', '产品名必须填写'],
        ['head_img','require','头图必须添加'],
        ['main_img_url','require','主图必须添加'],
        ['imgs_url','require','产品详情图必须添加'],
        ['introduce','require','产品介绍必须添加'],
        ['solu_cate_id', 'number'],
        ['id', 'number'],
        ['status', 'number|in:-1,0,1', '状态必须是数字|状态范围不合法']
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id','name','head_img','introduce','solu_cate_id'],
        'status'      => ['id','status']
    ];
}