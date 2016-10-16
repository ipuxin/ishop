<?php
namespace app\modules\controllers;

use app\common\helps\Tools;
use yii\web\Controller;
use app\modules\models\Admin;
use Yii;
use yii\data\Pagination;
use app\models\User;
use app\models\Profile;

class ManageController extends CommonController
{
    //显示管理员列表
    public function actionManages()
    {
        //2.查询出数据传递给视图(实现分页)
//        $managers=Admin::find()->all();
        $model = Admin::find();
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['manage'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $managers = $model->offset($pager->offset)->limit($pager->limit)->all();

        //1.配置视图文件
        $this->layout = 'layout1';
//        return $this->render('manages',['managers'=>$this->managers]);
        //生成带页码的需要传给视图参数
        return $this->render('manages', ['managers' => $managers, 'pager' => $pager]);

    }

    public function actionMailchangepass()
    {
        //接收参数
        $time = Yii::$app->request->get('timestamp');
        $adminuser = Yii::$app->request->get('adminuser');
        $token = Yii::$app->request->get('token');
        $model = new Admin;
        $myToken = $model->createToken($adminuser, $time);

        //参数校验
        if ($token != $myToken) {
//            Yii::$app->session->setFlash('infoTokenError', 'Token is error,请重新找回!');
            $this->redirect(['public/login']);
            Yii::$app->end();
        }

        if (time() - $time > 3000) {
//            Yii::$app->session->setFlash('infoTimeError', '链接时间过期,请重新找回!');
            $this->redirect(['public/login']);
            Yii::$app->end();
        }

        //修改密码
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changePass($post)) {
//                $this->redirect(['public/login']);
                Yii::$app->session->setFlash('info', '密码修改成功');
            } else {
                Yii::$app->session->setFlash('info', '密码修改失败');
            }
        }

        $model->adminuser = $adminuser;
        $this->layout = false;
        return $this->render('mailchangepass', ['model' => $model]);

    }

    //添加管理员
    public function actionReg()
    {
        //2.创建model给视图的表单用
        $model = new Admin;

        //3.用户填写数据后,判断是否合理
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();

            //4.把表单数据传递给model层处理,对数据进行校验和添加
            if ($model->reg($post)) {
                Yii::$app->session->setFlash('info', '添加成功');
            } else {
                Yii::$app->session->setFlash('info', '添加失败');
            }
        }
        $model->adminpass = '';
        $model->repass = '';

        //1.创建视图文件
        $this->layout = 'layout1';
        return $this->render('reg', ['model' => $model]);
    }

    //删除管理员
    public function actionDel()
    {
        $adminid = (int)Yii::$app->request->get('adminid');
        if (empty($adminid)) {
            return show(0, 'id为空', '');
//            $this->redirect(['manage/manages']);
        }

        //不能删除自己
//        var_dump(Yii::$app->session['admin']['adminid']);
//        var_dump($adminid);
//var_dump($sessionAdminid);
        $sessionAdminid = (int)Yii::$app->session['admin']['adminid'];
        if ($adminid == $sessionAdminid) {
            return Tools::show(0, '你不能删除自己', '');
        } else if (!$sessionAdminid) {
            return Tools::show(0, 'session为空', '');
        } else {
            $model = new Admin;
            if ($model->deleteAll('adminid=:id', [':id' => $adminid])) {
                return Tools::show(1, '删除成功', '');
//            return Tools::show(1,'删除成功',\yii\helpers\Url::to(['manage/reg']));
//            Yii::$app->session->setFlash('info', '删除成功');
//            $this->redirect(['manage/manages']);
            }
        }


    }

    //修改管理员邮箱
    public function actionChangeemail()
    {

        $this->layout = 'layout1';
        $model = Admin::find()->where('adminuser=:user', [':user' => Yii::$app->session['admin']['adminuser']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changeemail($post)) {
                Yii::$app->session->setFlash('info', '您的邮箱已更改!');
            }
        }
        $model->adminpass = '';
        return $this->render('changeemail', ['model' => $model]);
    }

    //修改管理员密码
    public function actionChangepass()
    {
        $this->layout = 'layout1';
        $model = Admin::find()->where('adminuser=:user', [':user' => Yii::$app->session['admin']['adminuser']])->one();
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if ($model->changepass($post)) {
                Yii::$app->session->setFlash('info', '您的密码已修改成功!');
            }
        }
        $model->adminpass = '';
//        $model->repass='';
        return $this->render('changepass', ['model' => $model]);
    }


}