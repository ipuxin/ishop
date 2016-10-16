<?php
namespace app\controllers;

use app\controllers\CommonController;
use Yii;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Cart;
use app\models\Product;
use app\models\User;
use app\models\Address;
use app\models\Pay;
use dzer\express\Express;

class OrderController extends CommonController
{
    //显示前台订单列表
    public function actionIndex()
    {
        $this->layout = "layout2";
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;

        //查询该用户的订单列表
        $orders = Order::getProducts($userid);
        return $this->render("index", ['orders' => $orders]);
    }

    //确认订单,提交后
    public function actionConfirm()
    {
        //addressid, expressid, status, amount(orderid,userid)总价计算
        try {
            if (Yii::$app->session['isLogin'] != 1) {
                return $this->redirect(['member/auth']);
            }

            if (!Yii::$app->request->isPost) {
                throw new \Exception();
            }

            $post = Yii::$app->request->post();
            $loginname = Yii::$app->session['loginname'];
            $usermodel = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one();
            if (empty($usermodel)) {
                throw new \Exception();
            }

            $userid = $usermodel->userid;

            //查询订单,更改订单状态为:待支付
            $model = Order::find()->where('orderid = :oid and userid = :uid', [':oid' => $post['orderid'], ':uid' => $userid])->one();
            if (empty($model)) {
                throw new \Exception();
            }
            $model->scenario = "update";
            $post['status'] = Order::CHECKORDER;
            //查询订单详情,获取订单中所有商品
            $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $post['orderid']])->all();
            $amount = 0;
            //计算总价
            foreach ($details as $detail) {
                $amount += $detail->productnum * $detail->price;
            }
            if ($amount <= 0) {
                throw new \Exception();
            }
            //加上快递价格
            $express = Yii::$app->params['expressPrice'][$post['expressid']];
            if ($express < 0) {
                throw new \Exception();
            }
            $amount += $express;

            $post['amount'] = $amount;
            $data['Order'] = $post;
            if ($model->load($data) && $model->save()) {
                return $this->redirect(['order/pay', 'orderid' => $post['orderid'], 'paymethod' => $post['paymethod']]);
            }
        } catch (\Exception $e) {
            return $this->redirect(['index/index']);
        }
    }

    //提交订单(显示商品图片,标题,价格,用户地址等)
    public function actionCheck()
    {
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }

        //确认订单状态为是否为:CREATEORDER = 0 订单初始化 而且 CHECKORDER = 100 待支付;
        $orderid = Yii::$app->request->get('orderid');
        $status = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one()->status;
        if ($status != Order::CREATEORDER && $status != Order::CHECKORDER) {
            return $this->redirect(['order/index']);
        }

        //获取用户地址
        $loginname = Yii::$app->session['loginname'];
        $userid = User::find()->where('username = :name or useremail = :email', [':name' => $loginname, ':email' => $loginname])->one()->userid;
        $addresses = Address::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();

        //获取订单详细信息
        $details = OrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->asArray()->all();
        $data = [];
        foreach ($details as $detail) {
            $model = Product::find()->where('productid = :pid', [':pid' => $detail['productid']])->one();
            $detail['title'] = $model->title;
            $detail['cover'] = $model->cover;
            $data[] = $detail;
        }

        //获取快递名称和价格
        $express = Yii::$app->params['express'];
        $expressPrice = Yii::$app->params['expressPrice'];
        $this->layout = "layout1";
        return $this->render("check", ['express' => $express, 'expressPrice' => $expressPrice, 'addresses' => $addresses, 'products' => $data]);
    }

    //生成订单
    public function actionAdd()
    {
        //如果没有登录则登录
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }

        //开启事务保证数据完整
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                $ordermodel = new Order;
                //设定前置验证场景为:add
                $ordermodel->scenario = 'add';
                //查询用户信息,根据用户名或者邮箱
                $usermodel = User::find()->where('username = :name or useremail = :email', [':name' => Yii::$app->session['loginname'], ':email' => Yii::$app->session['loginname']])->one();
                //如果没有数据
                if (!$usermodel) {
                    throw new \Exception('用户不存在');
                }
                $userid = $usermodel->userid;
                $ordermodel->userid = $userid;

                //设定订单状态为:订单初始化
                $ordermodel->status = Order::CREATEORDER;
                $ordermodel->createtime = time();

                //保存订单表
                if (!$ordermodel->save()) {
                    throw new \Exception('订单保存失败');
                }

                //获取刚添加的订单表的逐渐ID
                $orderid = $ordermodel->getPrimaryKey();

                //把前台提交的二维数组数据,遍历存入 订单详情表
                foreach ($post['OrderDetail'] as $product) {
                    $model = new OrderDetail;
                    //与订单表关联的字段,order_detail表中price已有数据
                    $product['orderid'] = $orderid;
                    $product['createtime'] = time();
                    $data['OrderDetail'] = $product;
                    if (!$model->add($data)) {
                        throw new \Exception();
                    }
                    //购物车清空
                    Cart::deleteAll('productid = :pid', [':pid' => $product['productid']]);
                    //商品数量减少
                    Product::updateAllCounters(['num' => -$product['productnum']], 'productid = :pid', [':pid' => $product['productid']]);
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
            return $this->redirect(['cart/index']);
        }
        return $this->redirect(['order/check', 'orderid' => $orderid]);
    }

    public function actionPay()
    {
        try {
            if (Yii::$app->session['isLogin'] != 1) {
                throw new \Exception();
            }
            $orderid = Yii::$app->request->get('orderid');
            $paymethod = Yii::$app->request->get('paymethod');
            if (empty($orderid) || empty($paymethod)) {
                throw new \Exception();
            }
            if ($paymethod == 'alipay') {
                return Pay::alipay($orderid);
            }
        } catch (\Exception $e) {
        }
        return $this->redirect(['order/index']);
    }

    //获取物流信息
    public function actionGetexpress()
    {
        //获取物流单号
        $expressno = Yii::$app->request->get('expressno');
        //传入物流单号,返回物流信息
        $res = Express::search($expressno);
        echo $res;
        exit;
    }

    //前台确认收货
    public function actionReceived()
    {
        $orderid = Yii::$app->request->get('orderid');
        $order = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one();
        //如果有数据,且订单状态为已发货,就更新为确认收货
        if (!empty($order) && $order->status == Order::SENDED) {
            $order->status = Order::RECEIVED;
            $order->save();
        }
        return $this->redirect(['order/index']);
    }

}








