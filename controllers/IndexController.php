<?php
namespace app\controllers;

//引入自定义控制器
use app\controllers\CommonController;

class IndexController extends CommonController
{
    public function actionIndex()
    {
        $this->layout = 'layout1';
        return $this->render('index');
    }
}