<?php

namespace app\modules\controllers;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Product;
use app\models\User;
use app\models\Address;
use yii\web\Controller;
use yii\data\Pagination;
use Yii;
use app\modules\controllers\CommonController;

class OrderController extends CommonController
{
    //后台订单列表
    public function actionList()
    {
        $this->layout = "layout1";
        $model = Order::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['order'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        //获取所有订单信息
        $data = $model->offset($pager->offset)->limit($pager->limit)->all();

        //获取完整订单信息
        $data = Order::getDetail($data);
        return $this->render('list', ['pager' => $pager, 'orders' => $data]);
    }

    //后台显示商品详细信息
    public function actionDetail()
    {
        $this->layout = "layout1";
        $orderid = (int)Yii::$app->request->get('orderid');
        $order = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one();
        $data = Order::getData($order);
        return $this->render('detail', ['order' => $data]);
    }

    //发货处理
    public function actionSend()
    {
        $this->layout = "layout1";
        $orderid = (int)Yii::$app->request->get('orderid');
        $model = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one();
        $model->scenario = "send";
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            $model->status = Order::SENDED;
            if ($model->load($post) && $model->save()) {
                Yii::$app->session->setFlash('info', '发货成功');
            }
        }
        return $this->render('send', ['model' => $model]);
    }

}







