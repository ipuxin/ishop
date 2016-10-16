<?php
//用户详细信息表
namespace app\models;

use yii\db\ActiveRecord;

class Profile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%profile}}';
    }


    public function rules()
    {
        return [
            ['nickname', 'required', 'message' => '用户名不能为空', 'on' => ['update']],
            [['sex', 'age','birthday','truename','company','createtime'], 'safe', 'message' => '性别不能为空', 'on' => ['update']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'truename' => '真实姓名',
            'nickname' => '用户昵称',
            'age' => '用户年龄',
            'sex' => '用户性别',
            'birthday' => '用户生日',
            'company' => '工作公司',
            'createtime' => '更新日期',

        ];
    }

}