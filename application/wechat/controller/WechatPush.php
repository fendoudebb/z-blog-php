<?php

namespace app\wechat\controller;


use app\admin\controller\Base;

class WechatPush extends Base {

    function push() {
        $echostr = strval(input('get.echostr'));
        return $echostr;
    }

    /*function checkSign() {
        $signature = strval(input('get.signature'));
        $timestamp = strval(input('get.timestamp'));
        $nonce = strval(input('get.nonce'));
        $token = TOEKN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if ($tmpStr == $signature ) {
            return true;
        } else {
            return false;
        }
    }*/

}