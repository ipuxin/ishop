<?php
//----------------testBegin
$fopen = fopen('comeNotify.php', 'a');
$num = 'comeNotify.php飘过1<br>'.'<br><br>';
//文件写
fwrite($fopen, $num);

//文件关闭
fclose($fopen);
//---------------------testEnd
    $url = "http://imshop.ipuxin.com/index.php?r=pay/notify";
    $post_data = $_POST;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $out = curl_exec($ch);
    curl_close($ch);
    echo $out;
//----------------testBegin
$fopen = fopen('comeNotify.php', 'a');
$num = 'comeNotify.php飘过2<br>'.'<br><br>';
//文件写
fwrite($fopen, $num);
//文件关闭
fclose($fopen);
//---------------------testEnd


