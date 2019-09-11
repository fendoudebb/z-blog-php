<?php


namespace app\admin\controller;


use app\common\util\IpUtil;
use app\common\util\Mongo;

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
        if (empty($cmdArr)) {
            return;
        }
        $ip = $cmdArr[0]->ip;
        $ipUtil = new IpUtil();
        $ipUtil->getAddressByIp($ip);
    }

}