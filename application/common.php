<?php

// 应用公共文件
function now() {
    return date("Y-m-d H:i:s");
}

function doGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_TIMEOUT, 3);
    $result = curl_exec($curl);
    $error = curl_error($curl);
    curl_close($curl);
    if ($error) throw new Exception('请求发生错误：' . $error);
    return $result;
}