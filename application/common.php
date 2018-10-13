<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 状态
 * @param $status
 * @return string
 */
function status($status){
    if($status == 1){
        $str = "<span class='label label-success radius'>正常</span>";
    }else if($status == 0){
        $str = "<span class='label label-danger radius'>待审</span>";
    }else{
        $str = "<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

function attr($attr,$attr_name='暂无'){
    $str = '';
    switch ($attr){
        case 1:
            $str = "<span class='label label-success radius'>$attr_name</span>";
            break;
        case 2:
            $str = "<span class='label label-danger radius'>$attr_name</span>";
            break;
        default:
            $str = "<span class='label label-default radius'>$attr_name</span>";
    }
    return $str;
}

/**
 * 无限级分类
 * @param $arr
 * @param int $pid
 * @param int $level
 * @param string $html
 * @return array
 */
function sortData($arr,$pid=0,$level=0,$html="----"){
    $newArr = [];
    foreach ($arr as $k=>$v){
        if($v['parent_id']==$pid){
            $v['level'] = $level;
            $v['html'] = $html;
            $newArr[] = $v;
            $newArr = array_merge($newArr,sortData($arr,$v['id'],$v['level']+1));
        }
    }
    return $newArr;
};

/**
 * 检测某key是否存在数组中
 */
function keyInArray($data,$key){
    $_data = [];
    foreach ($data as $k => $v){
        $_data[] = $k;
    }
    if(in_array($key,$_data)){
        return true;
    }else{
        return false;
    }
};

/**
 * 分页封装
 */
function pagination($obj){
    if(!$obj){
        return '';
    }
    return $obj->render();
}

// 获取对应模板的view
function getTemplateView($template_id=0){
    $templateArr = config('template.template_type');
    foreach ($templateArr as $k=>$v){
//        var_dump($v['key'].'====='.$template_id);
//        var_dump($v['key'] == $template_id);
        if($v['key'] == $template_id){
            return $v['view'];
        }
    }
}

function getCategoryIdByName($name=''){
    $id = db('category')->where([
        'name' => $name,
        'status' => 1
    ])->value('id');
    if(!$id){
        $id = '/';
    }
    return $id;
}

function getNavClass($template_id=0){
    $templateArr = config('template.template_type');
    foreach ($templateArr as $k=>$v){
        if($v['key'] === $template_id){
            return $v['selClass'];
        }
    }
}

/**
 * 根据url链接字符串拆分成数组
 * $str: url链接字符串
 */
function getUrlArr($dataArr=[],$key){
    if(isset($key) && keyInArray($dataArr,$key)){
        foreach ($dataArr as $data){
            $data[$key] = explode(';',trim($data[$key],';'));
        }
    }
}