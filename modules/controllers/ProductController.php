<?php

namespace app\modules\controllers;

use app\models\Category;
use app\models\Product;
use yii\web\Controller;
use Yii;
use yii\data\Pagination;
use crazyfd\qiniu\Qiniu;

class ProductController extends CommonController
{
    public function actionList()
    {
        //商品分页
        $model = Product::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['product'];
        //调用分页类, 只需传递总数和每页数量
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        //准备数据,传入$pager和limit.
        $products = $model->offset($pager->offset)->limit($pager->limit)->orderBy('productid desc')->all();
        $this->layout = "layout1";
        return $this->render("products", ['pager' => $pager, 'products' => $products]);
    }

    //添加商品
    public function actionAdd()
    {
        $this->layout = "layout1";
        $model = new Product;
        $cate = new Category;
        $list = $cate->getOptions();
        unset($list[0]);


        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $model->scenario = 'add';

            //图片上传方法
            $pics = $this->upload();
            if (!$pics) {
                $model->addError('cover', '封面不能为空');
            } else {
                //把上传后的数据传递给post
                $post['Product']['cover'] = $pics['cover'];
                $post['Product']['pics'] = $pics['pics'];
            }
            //如果$pics 上传成功, 而且,写入数据库成功
            if ($pics && $model->add($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            } else {
                Yii::$app->session->setFlash('info', '添加失败');
            }
        }

        return $this->render("add", ['opts' => $list, 'model' => $model]);
    }

    //商品图片上传
    private function upload()
    {
        if ($_FILES['Product']['error']['cover'] > 0) {
            return false;
        }
        //调用七牛实现上传
        //AK:Rk7gjIMPPtmnU-aI0_ax-jH_t8JWXJrj0vXwuffJ
        //SK:S-vl9gcJPM9xz87pfVQRT0Xq75u3ne9xzMfUlao6
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $key = uniqid();
        //$_FILES['Product']['tmp_name']['cover']临时文件
        $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
        //存储完成后,把图片链接存入$cover,以备存入数据库.
        $cover = $qiniu->getLink($key);
        //封面图片上传完成,附加图片上传
        $pics = [];
        foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
            //如果本次循环图片上传有误,跳出此次循环进入下次
            if ($_FILES['Product']['error']['pics'][$k] > 0) {
                continue;
            }
            $key = uniqid();
            $qiniu->uploadFile($file, $key);
            $pics[$key] = $qiniu->getLink($key);
        }
        //返回的数组,经json化之后可以直接写入数据表中
        return ['cover' => $cover, 'pics' => json_encode($pics)];
    }

    //编辑商品,修改
    public function actionMod()
    {
        $this->layout = "layout1";
        $cate = new Category;
        //清空$list中的第一个"添加顶级分类"这个元素
        $list = $cate->getOptions();
        unset($list[0]);
        //为修改页面准备数据
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :id', [':id' => $productid])->one();


        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $model->scenario='update';

            $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
            //获取封面图片
            $post['Product']['cover'] = $model->cover;
            //如果修改时更改 封面 图片,并且 上传成功,则删除七牛上面的图
            if ($_FILES['Product']['error']['cover'] == 0) {
                //设置新的封面图片名
                $key = uniqid();
                //把新的文件存储到七牛
                $qiniu->uploadFile($_FILES['Product']['tmp_name']['cover'], $key);
                //把新的文件链接存储到表单
                $post['Product']['cover'] = $qiniu->getLink($key);
                $qiniu->delete(basename($model->cover));

            }
            $pics = [];
            foreach ($_FILES['Product']['tmp_name']['pics'] as $k => $file) {
                if ($_FILES['Product']['error']['pics'][$k] > 0) {
                    continue;
                }
                $key = uniqid();
                $qiniu->uploadfile($file, $key);
                $pics[$key] = $qiniu->getlink($key);
            }
            $post['Product']['pics'] = json_encode(array_merge((array)json_decode($model->pics, true), $pics));
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '修改成功');
            }
        }
        return $this->render('add', ['model' => $model, 'opts' => $list]);

    }

    //删除小图中的一个或几个
    public function actionRemovepic()
    {
        $key = Yii::$app->request->get("key");
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);
        //把数据库中的json格式转化为数组
        $pics = json_decode($model->pics, true);
        unset($pics[$key]);
        Product::updateAll(['pics' => json_encode($pics)], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/mod', 'productid' => $productid]);
    }

    //删除商品
    public function actionDel()
    {
        $productid = Yii::$app->request->get("productid");
        $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
        $key = basename($model->cover);
        $qiniu = new Qiniu(Product::AK, Product::SK, Product::DOMAIN, Product::BUCKET);
        $qiniu->delete($key);
        $pics = json_decode($model->pics, true);
        foreach ($pics as $key => $file) {
            $qiniu->delete($key);
        }
        Product::deleteAll('productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }

    //修改为上架
    public function actionOn()
    {
        $productid = Yii::$app->request->get("productid");
        Product::updateAll(['ison' => '1'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }

    //修改为下架
    public function actionOff()
    {
        $productid = Yii::$app->request->get("productid");
        Product::updateAll(['ison' => '0'], 'productid = :pid', [':pid' => $productid]);
        return $this->redirect(['product/list']);
    }


}
