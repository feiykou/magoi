<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class procate extends BaseValidate
{
    protected $rule = [
        ['name', 'require|max:1000', '类名必须填写|类名不能超过10个字符'],
        ['parent_id', 'number'],
        ['id', 'number'],
        ['status', 'number|in:-1,0,1', '状态必须是数字|状态范围不合法'],
        ['listorder', 'number'],
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id', 'name','parent_id'],
        'listorder'   => ['id','listorder'],
        'status'      => ['id','status']
    ];
}