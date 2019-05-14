<?php

namespace app\common\util;


class ElasticsearchUtil {

    public static function GET($url, $param = null) {
        return self::execCurl($url, "GET", $param);
    }

    public static function POST($url, $param) {
        return self::execCurl($url, "POST", $param);
    }

    public static function PUT($url, $param) {
        return self::execCurl($url, "PUT", $param);
    }

    public static function DELETE($url, $param) {
        return self::execCurl($url, "DELETE", $param);
    }

    public static function HEAD($url, $param) {
        return self::execCurl($url, "HEAD", $param);
    }

    private static function execCurl($url, $method, $param = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        if ($param != null) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($param));
        }
        switch ($method) {
            case "GET":
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POST, true);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }
        $result = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        if ($error) {
            return json_decode("{\"error\":\"$error\"}");
        }
        return json_decode($result);
    }

}