<?php
//支付完成后,支付宝会有同步和异步通知
namespace app\controllers;

use app\controllers\CommonController;
use app\models\Pay;
use Yii;

class PayController extends CommonController
{
    //由于是post提交,需关闭yii自带的表单验证
    public $enableCsrfValidation = false;

    //异步通知:更新订单状态
    public function actionNotify()
    {
        //----------------testBegin
        $fopen = fopen('comeNotify.php', 'a');
        $num = 'actionNotify飘过1 : '. var_dump(Yii::$app->request->isPost).'<br><br>'.'<br><br>';
        //文件写
        fwrite($fopen, $num);

        //文件关闭
        fclose($fopen);
        //---------------------testEnd

        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post();
            if (Pay::notify($post)) {
                echo "success";

                //testBegin文件打开,以追加的形式
                $fopen = fopen('comeNotify.php', 'a');
                $num = 'PayController actionNotiy<br>'.'<br><br>';
                //文件写
                fwrite($fopen, $num);

                //文件关闭
                fclose($fopen);
                //testEnd

                exit;
            }
            echo "fail";
            exit;
        }
    }

    //(同步通知:显示支付状态)支付完成后页面跳转处理
    public function actionReturn()
    {
        $this->layout = 'layout1';
        //获取支付宝返回的支付状态
        $status = Yii::$app->request->get('trade_status');
        if ($status == 'TRADE_SUCCESS') {
            $s = 'ok';
        } else {
            $s = 'no';
        }

        return $this->render("status", ['status' => $s]);
    }
}





