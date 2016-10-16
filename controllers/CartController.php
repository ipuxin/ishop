<?php
namespace app\controllers;
use app\controllers\CommonController;
use app\models\User;
use app\models\Cart;
use app\models\Product;
use Yii;

class CartController extends CommonController
{
    public function actionIndex()
    {
        //如果用户没有登录则跳转的用户登录界面
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        //获取用户id
        $userid = User::find()->where('username = :name', [':name' => Yii::$app->session['loginname']])->one()->userid;
        //查询出该用户购物车的数据
        $cart = Cart::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
        $data = [];
        //根据购物车中的信息,查询出完整的商品信息 eg:商品标题,商品封面图
        foreach ($cart as $k=>$pro) {
            $product = Product::find()->where('productid = :pid', [':pid' => $pro['productid']])->one();
            $data[$k]['cover'] = $product->cover;
            $data[$k]['title'] = $product->title;
            $data[$k]['productnum'] = $pro['productnum'];
            $data[$k]['price'] = $pro['price'];
            $data[$k]['productid'] = $pro['productid'];
            $data[$k]['cartid'] = $pro['cartid'];
        }
//        var_dump($data);print_r($data);exit('二维数组');

        $this->layout = 'layout1';
        return $this->render("index", ['data' => $data]);
    }

    //加入购物车
    public function actionAdd()
    {
        //如果用户没有登录则跳转的用户登录界面
        if (Yii::$app->session['isLogin'] != 1) {
            return $this->redirect(['member/auth']);
        }
        $userid = User::find()->where('username = :name', [':name' => Yii::$app->session['loginname']])->one()->userid;

        //如果为post提交(通过商品详情页提交)
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
//            $num = Yii::$app->request->post()['productnum'];
            $num = Yii::$app->request->post('productnum');
            $data['Cart'] = $post;
            $data['Cart']['userid'] = $userid;
        }

        //如果为get提交,则是通过列表提交,只获得了productid,所以要补充数量productnum,促销价或者售价
        if (Yii::$app->request->isGet) {
            $productid = Yii::$app->request->get("productid");
            //获取商品价格
            $model = Product::find()->where('productid = :pid', [':pid' => $productid])->one();
            //判断是否促销,如果促销就取促销价格,否则就取原价
            $price = $model->issale ? $model->saleprice : $model->price;
            $num = 1;
            $data['Cart'] = ['productid' => $productid, 'productnum' => $num, 'price' => $price, 'userid' => $userid];
        }

        //判断购物车中是否有该商品,有就增加一个否则就新增一个
        if (!$model = Cart::find()->where('productid = :pid and userid = :uid', [':pid' => $data['Cart']['productid'], ':uid' => $data['Cart']['userid']])->one()) {
            $model = new Cart;
        } else {
            $data['Cart']['productnum'] = $model->productnum + $num;
        }

        $data['Cart']['createtime'] = time();
        $model->load($data);
        //save()方法可以是更新也可以是创建
        $model->save();

        return $this->redirect(['cart/index']);
    }

    //更改购物车中的商品数量,利用ajax接收来自前台的数据,更新购物车数据
    public function actionMod()
    {
        $cartid = Yii::$app->request->get("cartid");
        $productnum = Yii::$app->request->get("productnum");
        Cart::updateAll(['productnum' => $productnum], 'cartid = :cid', [':cid' => $cartid]);
    }

    public function actionDel()
    {
        $cartid = Yii::$app->request->get("cartid");
        Cart::deleteAll('cartid = :cid', [':cid' => $cartid]);
        return $this->redirect(['cart/index']);
    }

}





