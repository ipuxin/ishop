<p><?= $adminuser; ?>管理员,您好:</p>
<p>您的找回秘密链接如下:</p>
<?php
    $url=Yii::$app->urlManager->createAbsoluteUrl(['admin/manage/mailchangepass','timestamp'=>$time,'adminuser'=>$adminuser,'token'=>$token])
?>
<p><a href="<?= $url ?>"> <?= $url?></a></p>
<p>该链接5分钟内有效,请勿传递别人!</p><p>该邮件位系统自动发送,请勿回复!</p>