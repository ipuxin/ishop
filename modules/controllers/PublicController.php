<?php
/*
 * 用户登录
 */
namespace app\modules\controllers;

use yii\web\Controller;
use app\modules\models\Admin;
use Yii;

class PublicController extends Controller
{

    //登录验证
    public function actionLogin()
    {
        $this->layout = false;
        if(Yii::$app->session['admin']['isLogin']){
            //登录成功跳转到后台首页
            $this->redirect(['default/index']);
        }
        $model = new Admin;
        //如果有提交动作就开始判断是否登录成功
        if (Yii::$app->request->isPost) {
            //如果是post提交才执行判断
            $post = Yii::$app->request->post();

            //执行login()方法,成功执行跳转,否则,自动把错误信息传回视图
            if ($model->login($post)) {
                //登录成功跳转到后台首页
                $this->redirect(['default/index']);
                Yii::$app->end();
            }
        }
        return $this->render('login', ['model' => $model]);
    }

//    退出操作:清除session
    public function actionLogout()
    {
        //清除session,没有返回值
        Yii::$app->session->removeAll();
        //判断是否清
        if (!isset(Yii::$app->session['admin']['isLogin'])) {
            Yii::$app->session->setFlash('outSafe','安全退出成功');
            //清空,返回到登录界面
            $this->redirect(['public/login']);
            Yii::$app->end();
        } else {
            $this->goBack();
        }
    }

    //管理员找回密码,发送邮件
    public function actionSeekpassword()
    {
        $this->layout = false;
        $model = new Admin;

        //如果有post提交过来数据
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->seekPass($post)) {
                //信息提示
                Yii::$app->session->setFlash('info', '邮件已发送请注意查收');
            }
        }
        return $this->render('seekpassword', ['model' => $model]);
    }
}

