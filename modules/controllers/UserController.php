<?php
namespace app\modules\controllers;

use app\models\Profile;
use app\models\User;
use yii\web\NotFoundHttpException;
use app\common\helps\Tools;
use yii\web\Controller;

use Yii;
use yii\data\Pagination;

class UserController extends CommonController
{

    //显示用户列表
    public function actionUsers()
    {
        $model = User::find()->joinWith('profile');
        $count = $model->count();
        $pageSize = Yii::$app->params['pageSize']['manage'];
        $pager = new Pagination(['totalCount' => $count, 'pageSize' => $pageSize]);
        $users = $model->offset($pager->offset)->orderBy('userid desc')->limit($pager->limit)->all();

        $this->layout = 'layout1';
        return $this->render('users', ['users' => $users, 'pager' => $pager]);
    }

    //添加用户
    public function actionReg()
    {

        $model = new User;
        $proModel = new Profile();
        //判断如果有post提交,则提交到model层处理
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
//            var_dump($post);exit;
            //如果reg方法返回真, 则表明添加成功
            if ($model->reg($post)) {
//                return Tools::show(1,'保存成功','');
                Yii::$app->session->setFlash('info', '添加成功');
            } else {
//                return Tools::show(0,'添加失败','');
                Yii::$app->session->setFlash('info', '添加失败');
            }

        }
        $model->userpass = '';
        $model->repass = '';

        $this->layout = 'layout1';
        return $this->render('reg', ['model' => $model, 'proModel' => $proModel]);
    }

    //删除用户(使用事务)
    public function actionDel()
    {
        try {
            //用get方法获取用户id
            $userid = (int)Yii::$app->request->get('userid');
            if (empty($userid)) {
                return Tools::show(0, 'id 不正确', '');
//                throw new \Exception('id 不正确');
            }

            //开启事务
            $trans = Yii::$app->db->beginTransaction();
            //先删除从表,如果成功再删除主表
            if ($obj = Profile::find()->where('userid=:id', [':id' => $userid])->one()) {
                $res = Profile::deleteAll('userid=:id', [':id' => $userid]);
                if (empty($res)) {
                    return Tools::show(0, '删除从表失败', '');
//                    throw new \Exception('删除从表失败');
                }
            } else {
                return Tools::show(0, '附表数据不存在', '');
            }
            //删除主表
            if (!User::deleteAll('userid=:id', [':id' => $userid])) {
                return Tools::show(0, '删除主表失败', '');
//                throw new \Exception('删除主表失败');
            }
            //以上操作都没问题,提交执行
            $trans->commit();

        } catch (\Exception $e) {
            //出现异常回滚
            if (Yii::$app->db->getTransaction()) {
                $trans->rollBack();
                $message = $e->getMessage();
                return Tools::show(0, $message, '');
//                Yii::$app->session->setFlash('info',$message);
            }
        }
        return Tools::show(1, '删除主成功', '');
//        $this->redirect(['user/users']);
    }

    //后台修改用户信息
    public function actionEdit($userId)
    {
        //取得用户ID
//        $id = (int)Yii::$app->request->get('userId');
        $id=$userId;

        //关联获取两张表中该用户的信息
//        $model = User::find()->where('shop_user.userid=:id', [':id' => $userId])->joinWith('profile')->one();

        //取得用户信息主表
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException("该用户未找到.");
        }
        //取得用户信息附表
        $profile = Profile::findOne(['userid' =>$id]);
        if (!$profile) {
            throw new NotFoundHttpException("该用户没有详细信息.");
        }

        //如果有post提交,执行更新操作
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
//            var_dump( $profile->load(Yii::$app->request->post()));exit;
            $user->scenario = 'update';
            $profile->scenario='update';

            if ($user->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
                $isValid = $user->validate();
                $isValid = $profile->validate() && $isValid;
                if ($isValid) {
                    $user->save(false);
                    $profile->save(false);
                    Yii::$app->session->setFlash('info', '修改成功');
                    return $this->redirect(['edit', 'userId' => $id]);
                }
            }
        }
        $this->layout = 'layout1';
        return $this->render('edit', ['user' => $user,'profile'=>$profile]);
    }

}
