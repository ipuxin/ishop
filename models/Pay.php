<?php
namespace app\models;

use app\models\Order;
use app\models\OrderDetail;
use app\models\Product;


class Pay{
    public static function alipay($orderid)
    {

        $amount = Order::find()->where('orderid = :oid', [':oid' => $orderid])->one()->amount;
        if (!empty($amount)) {
            require_once('../vendor/AliPay/AlipayPay.php');
            $alipay = new \AlipayPay();
            //准备商品名称
            $giftname = "壹朴心商城";
            $data = OrderDetail::find()->where('orderid = :oid', [':oid' => $orderid])->all();
            $body = "";
            foreach($data as $pro) {
                $body .= Product::find()->where('productid = :pid', [':pid' => $pro['productid']])->one()->title . " - ";
            }
            $body .= "等商品";
            $showUrl = "http://imshop.ipuxin.com";
            $html = $alipay->requestPay($orderid, $giftname, $amount, $body, $showUrl);
            echo $html;
        }

        
    }

    //支付宝异步信息传入
    public static function notify($data)
    {

        $alipay = new \AlipayPay();
        //获取验证结果
        $verify_result = $alipay->verifyNotify();

        //testBegin文件打开,以追加的形式
        $fopen = fopen('comeNotify.php', 'a');
        $num = '$verify_result : '.$verify_result.'<br>';

        //文件写
        fwrite($fopen, $num);

        //文件关闭
        fclose($fopen);
        //testEnd

        if ($verify_result) {


            //接收订单ID
            $out_trade_no = $data['extra_common_param'];
            //接收订单状态
            $trade_no = $data['trade_no'];
            $trade_status = $data['trade_status'];
            //设置默认订单状态为:支付失败
            $status = Order::PAYFAILED;

            //------------------testBegin文件打开,以追加的形式
            $fopen = fopen('comeNotify.php', 'a');
            $num = '$verify_result : '.$verify_result.'<br>
            extra_common_param : '.$out_trade_no.'<br>
            trade_no : '. $trade_no .'<br> $trade_status : '.$trade_status.'<br>'.'<br><br>';
            //文件写
            fwrite($fopen, $num);

            //文件关闭
            fclose($fopen);
            //----------------------testEnd


            if ($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                //更改订单状态为202PAYSUCCESS,支付成功
                $status = Order::PAYSUCCESS;
                //查找刚才支付完成后的订单
                $order_info = Order::find()->where('orderid = :oid', [':oid' => $out_trade_no])->one();
                if (!$order_info) {
                    return false;
                }
                //------------------testBegin文件打开,以追加的形式
                $fopen = fopen('comeNotify.php', 'a');
                $num = '$order_info : '.print_r($order_info).'<br> <br> $order_info->status : '.$order_info->status.'<br><br>'.'<br><br>';
                //文件写
                fwrite($fopen, $num);

                //文件关闭
                fclose($fopen);
                //----------------------testEnd
                //如果订单状态为:待支付,就更新数据库
                if ($order_info->status == Order::CHECKORDER) {
                    echo 'status : '.$status.'<br>';
                    echo 'tradeno : '. $trade_no.'<br>';

                    Order::updateAll(['status' => $status, 'tradeno' => $trade_no, 'tradeext' => json_encode($data)], 'orderid = :oid', [':oid' => $order_info->orderid]);
                    echo 'status2 : '.$status.'<br>';
                    echo 'tradeno2 : '. $trade_no.'<br>';

                    exit();
                } else {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

}
