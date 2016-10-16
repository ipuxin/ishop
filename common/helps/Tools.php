<?php
//把报错信息以json形式返回,服务器端判断登录时用户名为空时报的错误
namespace app\common\helps;

class Tools
{
    public static function show($status, $message, $data)
    {
        $result = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];
        exit(json_encode($result));
    }
}