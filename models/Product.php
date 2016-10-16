<?php

namespace app\models;

use yii\db\ActiveRecord;

class Product extends ActiveRecord
{
    const AK = 'Rk7gjIMPPtmnU-aI0_ax-jH_t8JWXJrj0vXwuffJ';
    const SK = 'S-vl9gcJPM9xz87pfVQRT0Xq75u3ne9xzMfUlao6';
    const DOMAIN = 'obgv4vapj.bkt.clouddn.com';
    const BUCKET = 'imshop';

    //定义存储商品种类,显示在前台商品订单列表
    public $cate;

    public function rules()
    {
        return [
            ['title', 'required', 'message' => '标题不能为空', 'on' => ['add', 'update']],
            ['descr', 'required', 'message' => '描述不能为空', 'on' => ['add', 'update']],
            ['cateid', 'required', 'message' => '分类不能为空', 'on' => ['add', 'update']],
            ['price', 'required', 'message' => '单价不能为空', 'on' => ['add', 'update']],
            [['price', 'saleprice'], 'number', 'min' => 0.01, 'message' => '价格必须是数字', 'on' => ['add', 'update']],
            ['num', 'integer', 'min' => 0, 'message' => '库存必须是数字', 'on' => ['add', 'update']],
            [['issale', 'ishot', 'ison', 'pics', 'istui', 'cover'], 'safe', 'on' => ['add', 'update']],
            [['cover'], 'required', 'on' => 'add'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cateid' => '分类名称',
            'title' => '商品名称',
            'descr' => '商品描述',
            'price' => '商品价格',
            'ishot' => '是否热卖',
            'issale' => '是否促销',
            'saleprice' => '促销价格',
            'num' => '库存',
            'cover' => '封面图片',
            'pics' => '商品图片',
            'ison' => '是否上架',
            'istui' => '是否推荐',
        ];
    }

    public static function tableName()
    {
        return "{{%product}}";
    }

    //图片提交到七牛后
    public function add($data)
    {
        if ($this->load($data) && $this->save()) {
            return true;
        }
        return false;
    }


}
