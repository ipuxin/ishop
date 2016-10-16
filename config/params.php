<?php

return [
    'adminEmail' => 'admin@example.com',
    //分页大小
    'pageSize' => [
        //后台管理员列表,用户列表每页大小
        'manage' => 5,
        //后台商品列表,每页大小
        'product' => 20,
        //前台商品列表页,每页大小
        'frontproduct' => 6,
        //后台订单管理列表
        'order'=>5
    ],

    //用户无头像时显示此图
    'defaultValue' => [
        'avatar' => 'assets/admin/img/contact-img.png',
    ],

    //快递名称
    'express' => [
        1 => '中通快递',
        2 => '顺丰快递',
        3 => '圆通快递',
        4 => '包邮'],
    //快递价格
    'expressPrice' => [
        1 => 15,
        2 => 20,
        3 => 10,
        4 => 0
    ]
];
