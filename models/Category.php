<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Category extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%category}}';
    }

    public function attributeLabels()
    {
        return [
            'parentid' => '上级分类',
            'title' => '分类名称'
        ];
    }

    //设置前置验证规则
    public function rules()
    {
        return [
            ['parentid', 'required', 'message' => '上级分类不能为空'],
            ['title', 'required', 'message' => '标题不能为空'],
            ['createtime', 'safe']
        ];
    }

    //执行添加
    public function add($data)
    {
        $data['Category']['createtime'] = time();
        if ($this->load($data) && $this->save()) {
            return true;
        }
        return false;
    }

    //查询所有分类,返回一个二维数组
    public function getData()
    {
        //此结果为对象,徐转化为数组
        $cates = self::find()->all();
        $cates = ArrayHelper::toArray($cates);
        return $cates;
    }

    //整理分类,返回一个有级别,有秩序的数据,即:父类,子类1,子类2...
    public function getTree($cates, $pid = 0)
    {
        $tree = [];
        foreach ($cates as $cate) {
            //如果parentid = $pid ,即为 含有子类, 故需遍历显示子类
            if ($cate['parentid'] == $pid) {
                //保存父类
                $tree[] = $cate;
                //递归出子类,保存子类,并拼接
                $tree = array_merge($tree, $this->getTree($cates, $cate['cateid']));
            }
        }
        return $tree;
    }

    //给根类加前缀来区分等级
    public function setPrefix($data, $p = '|---')
    {
        $tree = [];
        $num = 1;
        //存储已经设置过前缀的类别,类别=>前缀个数,eg:parentid=0,给一个前缀
        $prefix = [0 => 0];
        //current() 输出数组中的当前元素的值,$key为当前值的索引,也是该值所在的序列
        while ($val = current($data)) {
            $key = key($data);
            //如果已经进行了一次循环
            if ($key > 0) {
                //如果级别改变则增加前缀
                if ($data[$key - 1]['parentid'] != $val['parentid']) {
                    $num++;
                }
            }
            /*如果$prefix 中含有$val['parentid'], 说明已经为该元素设置过前缀了,说明是从新的父类,
            无须实用$num++,直接用设置过的$num就OK啦
            否则说明没有设置过,就是为父类下面的子类,就直接用$num++的值*/
            if (array_key_exists($val['parentid'], $prefix)) {
                //已经设置过前缀的,就把当前前缀的个数放到$num存储.
                $num = $prefix[$val['parentid']];
            }
            //拼接类别名称, 插入$num个前缀$p.
            $val['title'] = str_repeat($p, $num) . $val['title'];
            $prefix[$val['parentid']] = $num;
            $tree[] = $val;
            next($data);
        }
        return $tree;
    }

    //为下拉菜单组合数据
    public function getOptions()
    {
        $data = $this->getData();
        $tree = $this->getTree($data);
        $tree = $this->setPrefix($tree);
        //声明下拉列表的数组,并添加提示
        $options = ['添加顶级分类'];
        foreach ($tree as $cate) {
            $options[$cate['cateid']] = $cate['title'];
        }
        return $options;
    }

    //显示含有等级前缀的列表
    public function getCategoryList(){
        $data = $this->getData();
        $tree = $this->getTree($data);
        $tree = $this->setPrefix($tree);
        return $tree;
    }

    public static function getMenu()
    {
        //查询出所有顶级分类
        $top = self::find()->where('parentid = :pid', [":pid" => 0])->asArray()->all();
        $data = [];
        //查询出所有二级分类,
        //$top是一个包含所有顶级类别的数组
        foreach((array)$top as $k=>$cate) {
            //给顶级类别增加一个下标为:children的元素,赋给含有该元素本父类下所有子类的数组
            $cate['children'] = (array)self::find()->where("parentid = :pid", [":pid" => $cate['cateid']])->asArray()->all();
/*
array(5) {
  ["cateid"]=>string(1) "1"
  ["title"]=>string(6) "衣服"
  ["parentid"]=>string(1) "0"
  ["createtime"]=>string(1) "0"
  ["children"]=>array(3) {
        [0]=>array(4) {
          ["cateid"]=>string(1) "4"
          ["title"]=>string(6) "裙子"
          ["parentid"]=>string(1) "1"
          ["createtime"]=>string(1) "0"
        }
        [1]=>array(4) {
          ["cateid"]=>string(1) "5"
          ["title"]=>string(6) "裤子"
          ["parentid"]=>string(1) "1"
          ["createtime"]=>string(1) "0"
        }
        [2]=> array(4) {
          ["cateid"]=>string(1) "6"
          ["title"]=>string(6) "袜子"
          ["parentid"]=>string(1) "1"
          ["createtime"]=>string(1) "0"
        }
  }
}*/
            //重新组合 为$data数组
            $data[$k] = $cate;
        }
        return $data;
    }

}