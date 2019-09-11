<?php


namespace app\admin\controller;


use app\common\util\IpUtil;
use app\common\util\Mongo;
use think\Log;

class IpPoolSchedule {

    public function queryUnrecognizedIp() {
        $findUnrecognizedIpCmd = [
            'find' => 'ip_pool',
            'filter' => [
                'address' => [
                    '$exists' => false
                ],
            ],
            'projection' => [
                '_id' => 0,
                'ip' => 1
            ],
            'limit' => 1,
        ];
        $cmdArr = Mongo::cmd($findUnrecognizedIpCmd);

        Log::log($cmdArr);
        if (empty($cmdArr)) {
            return;
        }
        $ip = $cmdArr[0]->ip;
        Log::log($ip);
        $ipUtil = new IpUtil();
        $address = $ipUtil->getAddressByIp($ip);
        Log::log("get ip[$ip] - address[$address]");
    }

}