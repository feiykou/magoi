<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class FrientLink extends BaseValidate
{
    protected $regex = [ 'template_id' => '^\d'];
    protected $rule = [
        ['name', 'require', '类名必须填写'],
        ['id', 'number'],
        ['link_url', 'require|url', '链接必须填写|链接必须是有效的url地址'],
        ['listorder', 'number','排序必须是数字'],
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id', 'name','link_url'],
        'listorder'   => ['id','listorder'],
    ];
}