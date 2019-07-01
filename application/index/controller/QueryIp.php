<?php

namespace app\index\controller;


use app\common\util\IpUtil;

class QueryIp extends Base {

    public function queryIp() {
        $ip = trim(strval(input("post.ip")));

        $queryTime = date('Y-m-d H:i:s');
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            return json(['code' => -1, 'msg' => "[$queryTime]：不合法的IP地址~"]);
        }

        if(!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
            return json(['code' => -1, 'msg' => "[$queryTime]：内网地址~"]);
        }

        $ipUtil = new IpUtil();
        $address = $ipUtil->getAddressByIp($ip);
        $this->request->__set("address", $address);//防止EventTracing再次查询ip
        if ($address == null) {
            return json(['code' => -1, 'msg' => "[$queryTime]：查询失败，请再试一次~"]);
        }
        return json(['code' => 200, 'address' => $address]);
    }

    public function queryResult() {
        $result = strval(input("post.result"));

        $queryTime = date('Y-m-d H:i:s');
        $ipUtil = new IpUtil();
        $address = $ipUtil->getAddressByResult($result);
        $this->request->__set("address", $address);//防止EventTracing再次查询ip
        if ($address == null) {
            return json(['code' => -1, 'msg' => "[$queryTime]: 查询失败~"]);
        }
        return json(['code' => 200, 'address' => $address]);
    }

}