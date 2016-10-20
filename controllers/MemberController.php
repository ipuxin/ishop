<?php
namespace app\controllers;

use yii\web\Controller;
use app\models\User;
use Yii;

class MemberController extends CommonController
{
//    public $layout = false;

    /**
     * @return string|\yii\web\Response
     * 登录入口,
     * 判断正常登录 和第三方登录处理入口(显示第三方登录按钮)
     */
    public function actionAuth()
    {
        $model = new User;

        /**
         * 征程登陆验证
         */
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

    /**
     * 点击qq登录后,处理qq登录开始,
     * 随后显示跳转,该页面可以点击qq头像进行下一步操作
     * 点击后,进入回调函数:actionQqcallback()
     */
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

    /**
     * @return \yii\web\Response
     * 判断是否绑定过,
     * 如果绑定过跳转到首页,否则,进行绑定处理:
     */
    public function actionQqcallback()
    {
        require_once('../vendor/qqlogin/qqConnectAPI.php');

        /**
         * 获取用户授权后的信息
         * 取得accessToken和openid
         */
        $auth = new \OAuth();
        $accessToken = $auth->qq_callback();
        $openid = $auth->get_openid();

        /**
         * 根据accessToken和openid
         * 获取用户信息
         */
        $qc = new \QC($accessToken, $openid);
        $userinfo = $qc->get_user_info();

        /**
         * 存储用户信息
         */
        $session = Yii::$app->session;
        $session['userinfo'] = $userinfo;
        $session['openid'] = $openid;

        /**
         * 根据openid判断用户是否已经登录
         * 如果用户已经绑定,存储用户登录信息,之后跳转到首页
         */
        if (User::find()->where('openid=:openid', [':openid' => $openid])->one()) {
            $session['loginname'] = $userinfo['nickname'];
            $session['openid'] = $openid;
            $session['isLogin'] = 1;
            return $this->redirect(['index/index']);
        }

        //如果用户没有绑定,则让用户重新注册
        return $this->redirect(['member/qqreg']);
    }

    /**
     * @return string|\yii\web\Response
     * 新绑定,执行注册操作
     */
    public function actionQqreg()
    {
        //加载视图
        $this->layout = 'layout2';
        $model = new User;

        /**
         * 如果提交信息成功,就开始执行注册,
         * 否则跳回qq绑定页面
         */
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            $session = Yii::$app->session;
            $post['User']['openid'] = $session['openid'];

            /**
             * 开始注册,设置新的注册场景qqreg,
             * 如果祝成功,保存用户登录信息到session,之后跳转到首页
             */
            if ($model->reg($post, 'qqreg')) {
                $session['loginname'] = $session['userinfo']['nickname'];
                $session['isLogin'] = 1;
                return $this->redirect(['index/index']);
            }
            /**
             * yii2调试方法
             */
//            var_dump($model->getErrors());
        }
        return $this->render('qqreg', ['model' => $model]);
    }
}
