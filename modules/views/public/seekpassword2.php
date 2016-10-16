<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>
<!DOCTYPE html>
<html class="login-bg">

<head>
    <title>壹朴心商城 - 找回密码</title>
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
    <div class="span4 box">
        <div class="content-wrap">
            <h6>壹朴心商城 - 找回密码</h6>
            <!--                显示发送邮件成功提示-->
            <?php if (Yii::$app->session->hasFlash('info')) {
                echo Yii::$app->session->getFlash('info');
            } ?>
            <div class="form-group field-admin-adminuser">
                <p class="help-block help-block-error"></p>
                <?= $form->field($model, 'adminuser')
                    ->textInput(['class' => 'span12',
                        'id' => 'admin-adminuser',
                        'placeholder' => '管理员账号']); ?>
            </div>
            <div class="form-group field-admin-adminpass">
                <p class="help-block help-block-error"></p>
                <?= $form->field($model, 'adminemail')
                    ->textInput(['class' => 'span12',
                        'id' => 'admin-adminpass',
                        'placeholder' => '管理员邮箱']); ?>
            </div>
            <!--<a href="/index.php?r=admin%2Fpublic%2Fseekpassword" class="forgot">忘记密码?</a>-->
            <a href="<?= yii\helpers\Url::to(['public/login']) ?>" class="forgot">返回登录</a>
            <div class="form-group field-remember-me">

                <?= Html::submitButton('找回密码', ['class' => 'btn-glow primary login']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
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