<?php

return [
    "banner_type" => [
        [
            'id' => '1',
            'name' => '首页置顶',
            'description' => '首页轮播图'
        ],
        [
            'id' => '2',
            'name' => '文章置顶',
            'description' => '文章轮播图'
        ]
    ],
    'redictor_type' => [
        [
            'name'=>'文章内容页',
            'model' => 'Article',
            'type' => 1
        ],
        [
            'name'=>'产品内容页',
            'model' => 'Product',
            'type' => 2
        ]
    ]
];