<?php

namespace app\models;

use yii\db\ActiveRecord;
use app\models\OrderDetail;
use app\models\Product;
use app\models\Category;

class Order extends ActiveRecord
{
    const CREATEORDER = 0;
    const CHECKORDER = 100;
    const PAYFAILED = 201;
    const PAYSUCCESS = 202;
    const SENDED = 220;
    const RECEIVED = 260;

    public static $status = [
        self::CREATEORDER => '订单初始化',
        self::CHECKORDER => '待支付',
        self::PAYFAILED => '支付失败',
        self::PAYSUCCESS => '等待发货',
        self::SENDED => '已发货',
        self::RECEIVED => '订单完成',
    ];

    //存储订单中的所有商品列表
    public $products;
    //存储中文状态说明
    public $zhstatus;
    public $username;
    public $address;

    public function rules()
    {
        return [
            [['userid', 'status'], 'required', 'on' => ['add']],
            [['addressid', 'expressid', 'amount', 'status'], 'required', 'on' => ['update']],
            ['expressno', 'required', 'message' => '请输入快递单号', 'on' => 'send'],
            ['createtime', 'safe', 'on' => ['add']],
        ];
    }

    public static function tableName()
    {
        return "{{%order}}";
    }

    public function attributeLabels()
    {
        return [
            'expressno' => '快递单号',
        ];
    }

    //为了在后台显示完整的订单列表信息制定此方法
    public static function getDetail($orders)
    {
        //循环出每个订单
        foreach ($orders as $order) {
            $order = self::getData($order);
        }
        return $orders;
    }

    //根据每个订单信息,查出相关信息
    public static function getData($order)
    {
        $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $order->orderid])->all();
        $products = [];
        foreach ($details as $detail) {
            $product = Product::find()->where('productid = :pid', [':pid' => $detail->productid])->one();
            $product->num = $detail->productnum;
            $products[] = $product;
        }
        //存储订单中的所有商品列表
        $order->products = $products;
        $order->username = User::find()->where('userid = :uid', [':uid' => $order->userid])->one()->username;
        $order->address = Address::find()->where('addressid = :aid', [':aid' => $order->addressid])->one();
        if (empty($order->address)) {
            $order->address = "";
        } else {
            $order->address = $order->address->address;
        }
        $order->zhstatus = self::$status[$order->status];
        return $order;
    }

    //前台用户订单信息表
    public static function getProducts($userid)
    {
        //得到多个订单表(查询出所有状态符合的该用户的所有订单)
        $orders = self::find()->where('status > 0 and userid = :uid', [':uid' => $userid])->orderBy('createtime desc')->all();

        //得到单个订单表
        foreach ($orders as $order) {

            //得到单个订单详情表 (根据单个订单ID查询出 单个该订单 对应的 订单详情表)
            $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $order->orderid])->all();

            //存储商品信息数组
            $products = [];
            foreach ($details as $detail) {

                //得到订单详情表中商品 字段 信息
                $product = Product::find()->where('productid = :pid', [':pid' => $detail->productid])->one();
                //这里的数量用订单中购买数量取代
                $product->num = $detail->productnum;
                $product->price = $detail->price;
                $product->cate = Category::find()->where('cateid = :cid', [':cid' => $product->cateid])->one()->title;
                $products[] = $product;
            }
            //查询订单中文状态
            $order->zhstatus = self::$status[$order->status];

            $order->products = $products;
        }
        return $orders;
    }


}
