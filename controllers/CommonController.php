<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\Category;
use app\models\Cart;
use app\models\User;
use app\models\Product;
use Yii;

class CommonController extends Controller
{
    //Yii自动调用方法
    //此方式避免了:1.方法的重用如$menu. 2.layout中也可以使用 如:$menu.
    public function init()
    {
        //获取顶级,二级商品分类
        $menu = Category::getMenu();
        //把商品分类传递给模板
        $this->view->params['menu'] = $menu;

        //全局购物车信息: 如果登陆就显示购物车内的商品名称数量等信息
        $data = [];
        $data['products'] = [];
        $total = 0;
        //如果登陆了,就显示购物车动态信息
        if (Yii::$app->session['isLogin']) {
            $usermodel = User::find()->where('username = :name', [":name" => Yii::$app->session['loginname']])->one();
            if (!empty($usermodel) && !empty($usermodel->userid)) {
                $userid = $usermodel->userid;
                $carts = Cart::find()->where('userid = :uid', [':uid' => $userid])->asArray()->all();
                foreach ($carts as $k => $pro) {
                    $product = Product::find()->where('productid = :pid', [':pid' => $pro['productid']])->one();
                    $data['products'][$k]['cover'] = $product->cover;
                    $data['products'][$k]['title'] = $product->title;
                    $data['products'][$k]['productnum'] = $pro['productnum'];
                    $data['products'][$k]['price'] = $pro['price'];
                    $data['products'][$k]['productid'] = $pro['productid'];
                    $data['products'][$k]['cartid'] = $pro['cartid'];
                    $total += $data['products'][$k]['price'] * $data['products'][$k]['productnum'];
                }
            }
        }
        $data['total'] = $total;
        $this->view->params['cart'] = $data;

        //全局:推荐,热卖,促销商品
        //获取二级类别列表
        $cid = Yii::$app->request->get("cateid") ? Yii::$app->request->get("cateid") : '40';
        $where = "cateid = :cid and ison = '1'";
        $params = [':cid' => $cid];
        $model = Product::find()->where($where, $params);

        $tui = $model->where($where . ' and istui = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $hot = $model->where($where . ' and ishot = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $sale = $model->where($where . ' and issale = \'1\'', $params)->orderby('createtime desc')->limit(5)->asArray()->all();
        $new = Product::find(['ison' => '1'])->limit(5)->asArray()->all();
        $this->view->params['tui'] = $tui;
        $this->view->params['hot'] = $hot;
        $this->view->params['sale'] = $sale;
        $this->view->params['new'] = $new;

        //全局:浏览记录(找到最后8条记录,查出数据,传给视图)
        $cookies = \Yii::$app->request->cookies;
        $dataId = $cookies->getValue('browse');
        if (!empty($dataId)) {
            $proId = array_slice($dataId, -8, 8);
            //结果转化为数组
//        $browse = Product::find()->where(['productid' => $proId])->asArray()->all();
            //结果为对象
            $browse = Product::find('productid', 'title')->where(['productid' => $proId])->all();
            $this->view->params['browse'] = $browse;
        }
    }
}
