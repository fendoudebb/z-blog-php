<?php

namespace app\index\controller;


use app\common\util\IpUtil;

class QueryIp extends Base {

    public function queryIp() {
        $ip = strval(input("post.ip"));

        $queryTime = date('Y-m-d H:i:s');
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return json(['code' => -1, 'msg' => "[$queryTime]：不合法的IP地址~"]);
        }

        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            return json(['code' => -1, 'msg' => "[$queryTime]：内网地址~"]);
        }

        $ipUtil = new IpUtil();
        $address = $ipUtil->getAddressByIp($ip);
        if ($address == null) {
            return json(['code' => -1, 'msg' => "[$queryTime]：查询失败，请再试一次~"]);
        }
        return json(['code' => 200, 'address' => $address]);
    }

    public function parseResult() {
        $result = strval(input("post.result"));
        $ipUtil = new IpUtil();
        $address = $ipUtil->decodeResult($result);
        if ($address == null) {
            return json(['code' => -1, 'msg' => '查询失败~']);
        }
        return json(['code' => 200, 'address' => $ipUtil->parseAddress($address)]);
    }

}