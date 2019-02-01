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

function compressHtml($content) {
    $compressContent = '';
    $chunks = preg_split( '/(<pre.*?\/pre>)/ms', $content, -1, PREG_SPLIT_DELIM_CAPTURE );
    foreach ($chunks as $c) {
        if (strpos( $c, '<pre') !== 0) {
            $IE='<!--[if';
            $IE1='<!-【';
            $start='<!--';
            $end='-->';
            $start1='<!--@';
            $end1='@-->';
            $c = preg_replace('#\/\*.*\*\/#isU','',$c);//js块注释
            $c = preg_replace('#[^:"\']\/\/[^\n]*#','',$c);//js行注释
            $c = str_replace("\t","",$c);//tab
            $c = preg_replace('#\s?(=|>=|\?|:|==|\+|\|\||\+=|>|<|\/|\-|,|\()\s?#','$1',$c);//字符前后多余空格
            $c = preg_replace( '#([^"\'])[\s]+#', '$1 ', $c );
            $c = preg_replace( '#>\s<#', '><', $c );
            $c = str_replace("\t","",$c);//tab
            $c = str_replace("\r\n","",$c);//回车
            $c = str_replace("\r","",$c);//换行
            $c = str_replace("\n","",$c);//换行
            //去除html注释,忽略IE兼容
            $c=str_replace($IE,$IE1,$c);
            $c=str_replace($start,$start1,$c);
            $c=str_replace($end,$end1,$c);
            $c = preg_replace( '#(<![\-]{2}@[^@]*@[\-]{2}>)#', '', $c );
            $c=str_replace($IE1,$IE,$c);
            $c=str_replace($end1,$end,$c);
            $c = trim($c," ");
        }
        $compressContent .= $c;
    }
    return $compressContent;
}