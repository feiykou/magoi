<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class User extends BaseValidate
{
    protected $rule = [
        ['name', 'require', '登录名必须填写'],
        ['real_name', 'require', '姓名必须填写'],
        ['pwd', 'require', '密码必须填写'],
        ['id', 'number'],
        ['status', 'number|in:-1,0,1', '状态必须是数字|状态范围不合法']
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id', 'name','real_name','pwd'],
        'status'      => ['id','status']
    ];
}