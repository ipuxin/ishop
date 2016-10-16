<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use Yii;

class MemberController extends CommonController
{
//    public $layout = false;

    //前台页面显示 与 登录判断入口
    public function actionAuth()
    {
        $model = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->login($post)) {

//                return $this->redirect($_SERVER['HTTP_REFERER']);
//                return $this->goBack();
//                return $this->goBack(Yii::$app->request->referrer);
//                return $this->redirect(Yii::$app->user->getReturnUrl());
                return $this->redirect(['index/index']);
            }
        }
        $this->layout = 'layout2';
        return $this->render('auth', ['model' => $model]);
    }

    //安全退出
    public function actionLogout()
    {
        Yii::$app->session->remove('loginname');
        Yii::$app->session->remove('isLogin');
        if (!isset(Yii::$app->session['isLogin'])) {
            return $this->goBack(Yii::$app->request->referrer);
        }
    }

    //通过邮箱注册新用户
    public function actionReg()
    {
        $model = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->regByMail($post)) {
                Yii::$app->session->setFlash('info', '邮件发送成功!');
            }
        }
        $this->layout = 'layout2';
        return $this->render('auth', ['model' => $model]);
    }

    //qq登录跳转
    public function actionQqlogin()
    {
        require_once("../vendor/qqlogin/qqConnectAPI.php");
        $qc = new \QC();
        $qc->qq_login();

    }

    /*
     * $openid : F0ACAE2A4331D6FC7D3F50E5C443CBD0
array(18) {
    ["ret"]=> int(0)
    ["msg"]=> string(0) ""
    ["is_lost"]=> int(0)
    ["nickname"]=> string(6) "ipuxin"
    ["gender"]=> string(3) "男"
    ["province"]=> string(0) ""
    ["city"]=> string(0) ""
    ["year"]=> string(4) "1989"
    ["figureurl"]=> string(73) "http://qzapp.qlogo.cn/qzapp/101337109/F0ACAE2A4331D6FC7D3F50E5C443CBD0/30"
    ["figureurl_1"]=> string(73) "http://qzapp.qlogo.cn/qzapp/101337109/F0ACAE2A4331D6FC7D3F50E5C443CBD0/50"
    ["figureurl_2"]=> string(74) "http://qzapp.qlogo.cn/qzapp/101337109/F0ACAE2A4331D6FC7D3F50E5C443CBD0/100"
    ["figureurl_qq_1"]=> string(69) "http://q.qlogo.cn/qqapp/101337109/F0ACAE2A4331D6FC7D3F50E5C443CBD0/40"
    ["figureurl_qq_2"]=> string(70) "http://q.qlogo.cn/qqapp/101337109/F0ACAE2A4331D6FC7D3F50E5C443CBD0/100"
    ["is_yellow_vip"]=> string(1) "0"
    ["vip"]=> string(1) "0"
    ["yellow_vip_level"]=> string(1) "0"
    ["level"]=> string(1) "0"
    ["is_yellow_year_vip"]=> string(1) "0" }
     */

    public function actionQqcallback()
    {
        require_once('../vendor/qqlogin/qqConnectAPI.php');

        //获取用户信息
        $auth = new \OAuth();
        $accessToken = $auth->qq_callback();
        $openid = $auth->get_openid();
        //获取用户信息
        $qc = new \QC($accessToken, $openid);
        $userinfo = $qc->get_user_info();
        //存储用户信息
        $session = Yii::$app->session;
        $session['userinfo'] = $userinfo;
        $session['openid'] = $openid;

        //如果用户已经绑定
        if (User::find()->where('openid=:openid', [':openid' => $openid])->one()) {
            $session['loginname'] = $userinfo['nickname'];
            $session['openid'] = $openid;
            $session['isLogin'] = 1;
            return $this->redirect(['index/index']);
        }
        //如果用户没有绑定,则让用户重新注册
        return $this->redirect(['member/qqreg']);

    }

    //让用户绑定qq
    public function actionQqreg()
    {
        //加载视图
        $this->layout = 'layout2';
        $model = new User;
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $session = Yii::$app->session;
            $post['User']['openid'] = $session['openid'];

            if ($model->reg($post, 'qqreg')) {
                $session['loginname'] = $session['userinfo']['nickname'];
                $session['isLogin'] = 1;
                return $this->redirect(['index/index']);
            }
        }
        return $this->render('qqreg', ['model' => $model]);
    }
}
