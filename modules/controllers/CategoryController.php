<?php
namespace app\modules\controllers;

use yii\web\Controller;
use app\models\Category;
use Yii;

class CategoryController extends Controller
{
    //显示商品类别列表
    public function actionList()
    {
        $model = new Category();
        //显示含有等级前缀的列表
        $cates = $model->getCategoryList();
        $this->layout = 'layout1';
        return $this->render('cates', ['cates' => $cates]);
    }

    //添加商品类别
    public function actionAdd()
    {
        //$list,$model为要准备的数据
        $model = new Category;
        $list = $model->getOptions();

        //接收视图post数据,转交model层处理
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->add($post)) {
                Yii::$app->session->setFlash('info', '添加类别成功!');
                $this->redirect(['category/add']);
            }
        }
        $model->title = '';
        $this->layout = 'layout1';
        return $this->render('add', ['list' => $list, 'model' => $model]);
    }

    //编辑商品类别列表
    public function actionMod()
    {
        $cateid = Yii::$app->request->get('cateid');
        $model = Category::find()->where('cateid=:id', [':id' => $cateid])->one();
        $list = $model->getOptions();

        //执行修改
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');
            }
        }

        $this->layout = 'layout1';
        return $this->render('add', ['model' => $model, 'list' => $list]);
    }

    //删除操作
//    public function actionDel()
//    {
//
//        $model = new Category();
//        $cateid = (int)Yii::$app->request->get('cateid');
//        if ($cateid) {
//            //如果传进来的cateid是其他类的父类,则是有子类,有子类不能删除
//            $data = Category::find()->where('parentid=:id', [':id' => $cateid])->one();
//            //如果有子类
//            if ($data) {
//                Yii::$app->session->setFlash('info', '请清空子类才能删除该类');
//            } else {
//                $model->deleteAll('cateid=:id', [':id' => $cateid]);
//            }
//
//        }
//        //显示含有等级前缀的列表
//        $cates = $model->getCategoryList();
//        $this->layout = 'layout1';
//        return $this->render('cates', ['cates' => $cates]);
//    }

    public function actionDel()
    {

        $model = new Category();
        $cateid = (int)Yii::$app->request->get('cateid');
        try {
            if ($cateid) {
                //如果传进来的cateid是其他类的父类,则是有子类,有子类不能删除
                $data = Category::find()->where('parentid=:id', [':id' => $cateid])->one();
                //如果有子类
                if ($data) {
                    throw new \Exception('请清空子类才能删除该类');
                } else {
                    if ($model->deleteAll('cateid=:id', [':id' => $cateid])) {
                        throw new \Exception('已删除');
                    } else {
                        throw new \Exception('删除失败(请查看id是否合法)');
                    }
                }
            } else {
                throw new \Exception('没有传入id');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('info', $e->getMessage());
        }
        if ($cateid) {
            //如果传进来的cateid是其他类的父类,则是有子类,有子类不能删除
            $data = Category::find()->where('parentid=:id', [':id' => $cateid])->one();
            //如果有子类
            if ($data) {
                Yii::$app->session->setFlash('info', '请清空子类才能删除该类');
            } else {
                $model->deleteAll('cateid=:id', [':id' => $cateid]);
            }

        }
        //显示含有等级前缀的列表
        $cates = $model->getCategoryList();
        $this->layout = 'layout1';
        return $this->render('cates', ['cates' => $cates]);
    }

}