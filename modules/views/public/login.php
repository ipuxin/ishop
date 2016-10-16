<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html class="login-bg">

<head>
    <title>壹朴心商城 - 后台管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <!-- bootstrap -->
    <link href="assets/admin/css/bootstrap/bootstrap.css" rel="stylesheet"/>
    <link href="assets/admin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet"/>
    <link href="assets/admin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet"/>
    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="assets/admin/css/layout.css"/>
    <link rel="stylesheet" type="text/css" href="assets/admin/css/elements.css"/>
    <link rel="stylesheet" type="text/css" href="assets/admin/css/icons.css"/>
    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="assets/admin/css/lib/font-awesome.css"/>
    <!-- this page specific styles -->
    <link rel="stylesheet" href="assets/admin/css/compiled/signin.css" type="text/css" media="screen"/>
    <!-- open sans font -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body>
<div class="row-fluid login-wrapper">
    <?php
    $form = ActiveForm::begin([
        //去掉label
        'fieldConfig' => [
            'template' => '{error}{input}',
        ]
    ]);
    ?>
    <div class="span"></div>
    <div class="span4 box">
        <div class="content-wrap">
            <h6>壹朴心商城 - 后台登录</h6>
            <?php if (Yii::$app->session->hasFlash('outSafe')) {
                echo Yii::$app->session->getFlash('outSafe');
            } ?>
            <?php if (Yii::$app->session->hasFlash('infoTokenError')) {
                echo Yii::$app->session->getFlash('infoTokenError');
            } ?>
            <?php if (Yii::$app->session->hasFlash('infoTimeError')) {
                echo Yii::$app->session->getFlash('infoTimeError');
            } ?>
            <?= $form->field($model, 'adminuser')
                ->textInput(['class' => 'span12', 'id' => 'admin-adminuser', 'placeholder' => '管理员账号']); ?>
            <?= $form->field($model, 'adminpass')
                ->passwordInput(['class' => 'span12', 'id' => 'admin-adminpass', 'placeholder' => '管理员密码']); ?>
            <a href="/index.php?r=admin%2Fpublic%2Fseekpassword" class="forgot">忘记密码?</a>
            <div class="form-group field-remember-me">
                <?= $form->field($model, 'rememberMe')
                    ->checkbox([
                        'id' => 'remember-me',
                        'template' => '<div class="remember">{input}<label for="remember-me">自动登陆</label></div>',
                ]) ?>
                <!--<div class="remember">
                    <input type="hidden" name="Admin[rememberMe]" value="0">
                    <input type="checkbox" id="remember-me" name="Admin[rememberMe]" value="1" checked>
                    <label for="remember-me">记住我</label></div>
                </div>-->
                <?= Html::submitButton('登~录', ['class' => 'btn-glow primary login']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<!-- scripts -->
<script src="assets/admin/js/jquery-latest.js"></script>
<script src="assets/admin/js/bootstrap.min.js"></script>
<script src="assets/admin/js/theme.js"></script>
<!-- pre load bg imgs -->
<script type="text/javascript">$(function () {
        // bg switcher
        var $btns = $(".bg-switch .bg");
        $btns.click(function (e) {
            e.preventDefault();
            $btns.removeClass("active");
            $(this).addClass("active");
            var bg = $(this).data("img");

            $("html").css("background-image", "url('img/bgs/" + bg + "')");
        });

    });</script>
</body>

</html>