<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;

?>
<link rel="stylesheet" href="assets/admin/css/compiled/new-user.css" type="text/css" media="screen"/>
<!-- main container -->
<div class="content">
    <div class="container-fluid">
        <div id="pad-wrapper" class="new-user">
            <div class="row-fluid header">
                <h3>后台修改前台用户信息(变更用户注册邮箱,登录名等)</h3></div>
            <div class="row-fluid form-wrapper">
                <!-- left column -->
                <div class="span9 with-sidebar">

                    <div class="container">
                        <?php
                        if (Yii::$app->session->hasFlash('info')) {
                            echo Yii::$app->session->getFlash('info');
                        } ?>
                        <?php
                        $form = ActiveForm::begin([
                            'options' => ['class' => 'new_user_form inline-input'],
                            'fieldConfig' => [
                                'template' => '{error}<div class="span12 field-box">{label}{input}</div>',
                            ]
                        ]);
                        ?>
                        <?= $form->field($user, 'username')->textInput(['class' => 'span9']); ?>
                        <?= $form->field($user, 'useremail')->textInput(['class' => 'span9']); ?>
                        <?= $form->field($profile, 'truename')->textInput(['class' => 'span9']); ?>
                        <?= $form->field($profile, 'nickname')->textInput(['class' => 'span9']); ?>

                        <?= $form->field($profile, 'age')->textInput(['class' => 'span9 label-group']); ?>

                        <?= $form->field($profile, 'sex')->inline()->radioList(['0'=>'保密','1'=>'男','2'=>'女'],['class' => 'span9'])?>

                        <?= $form->field($profile, 'birthday')->widget(DatePicker::classname(), [
                            'options' => ['placeholder' => '', 'class'=>'bootstrap-datepicker'],
                            'name' => 'dp_3',
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                                'format' => 'yyyy-mm-dd',
                            ]
                        ]); ?>
                        <?= $form->field($profile, 'company')->textInput(['class' => 'span9']); ?>
                        <?= $form->field($user, 'createtime')->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => '','value' => date('Y-m-d h:i:s', $profile['createtime']),],
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                            ]
                        ]); ?>
                        <?= $form->field($profile, 'createtime')->widget(DateTimePicker::classname(), [
                            'options' => ['placeholder' => '','value' => date('Y-m-d h:i:s', $profile['createtime']),],
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'pluginOptions' => [
                                'autoclose' => true,
                                'todayHighlight' => true,
                            ]
                        ]); ?>
                        <?= $form->field($user, 'userpass')->passwordInput(['class' => 'span9']); ?>
                        <?= $form->field($user, 'repass')->passwordInput(['class' => 'span9']); ?>

                        <div class="span11 field-box actions">
                            <?= Html::submitButton('创建', ['class' => 'btn-glow primary']) ?>
                            <span>或者</span>
                            <?= Html::submitButton('取消', ['class' => 'reset', 'type' => 'reset']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <!-- side right column -->
                <div class="span3 form-sidebar pull-right">
                    <div class="alert alert-info hidden-tablet">
                        <i class="icon-lightbulb pull-left"></i>请在左侧填写管理员相关信息，包括管理员账号，电子邮箱，以及密码
                    </div>
                    <h6>重要提示：</h6>
                    <p>管理员可以管理后台功能模块</p>
                    <p>请谨慎添加</p>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- end main container -->