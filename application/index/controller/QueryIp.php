<?php

namespace app\index\controller;


use app\common\util\IpUtil;

class QueryIp extends Base {

    public function queryIp() {
        $ip = strval(input("post.ip"));
        $ipUtil = new IpUtil();
        $address = $ipUtil->getAddressByIp($ip);
        if ($address == null) {
            return json(['code' => -1, 'msg' => '查询失败，请再试一次~']);
        }
        return json(['code' => 200, 'address' => $address]);
    }

}