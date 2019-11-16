<?php

namespace app\admin\controller;


use app\common\util\IpUtil;
use app\common\util\Mongo;
use DateTimeZone;
use MongoDB\BSON\UTCDateTime;

class IpPool extends BaseRoleNormal {

    public function ipPool() {
        $page = intval(input('post.page'));
        $size = intval(input('post.size'));
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;
        $cmd = [
            'find' => 'ip_pool',
            'projection' => [
                '_id' => 1,
                'ip' => 1,
                'address' => 1,
                'createTime' => 1
            ],
            'sort' => [
                '_id' => -1,
            ],
            'skip' => $offset,
            'limit' => $size,
        ];

        $ipPool = Mongo::cmd($cmd);

        foreach ($ipPool as $ip) {
            $ip->_id = $ip->_id->__toString();
            if ($ip->createTime instanceof UTCDateTime) {
                $dateTime = $ip->createTime->toDateTime();
                $dateTime->setTimezone(new DateTimeZone("Asia/Shanghai"));//date_default_timezone_get()
                $ip->createTime = $dateTime->format("Y-m-d H:i:s");
            }
        }

        $response = [
        ];
        $cmd = [
            'count' => 'ip_pool'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['ipPool'] = $ipPool;
        return $this->res($response);
    }

    public function unrecognizedIp() {
        $page = intval(input('post.page'));
        $size = intval(input('post.size'));
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;
        $cmd = [
            'find' => 'ip_pool',
            'filter' => [
                'address' => [
                    '$exists' => false
                ]
            ],
            'projection' => [
                '_id' => 1,
                'ip' => 1,
                'createTime' => 1
            ],
            'sort' => [
                '_id' => -1,
            ],
            'skip' => $offset,
            'limit' => $size,
        ];

        $ipPool = Mongo::cmd($cmd);

        foreach ($ipPool as $ip) {
            $ip->_id = $ip->_id->__toString();
            if ($ip->createTime instanceof UTCDateTime) {
                $dateTime = $ip->createTime->toDateTime();
                $dateTime->setTimezone(new DateTimeZone("Asia/Shanghai"));//date_default_timezone_get()
                $ip->createTime = $dateTime->format("Y-m-d H:i:s");
            }
        }

        $response = [
        ];
        $cmd = [
            'count' => 'ip_pool',
            'query' => [
                'address' => [
                    '$exists' => false
                ]
            ]
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['unrecognizedIp'] = $ipPool;
        return $this->res($response);
    }

    public function queryUnrecognizedIp() {
        $ip = input("post.ip/a");
        $ipUtil = new IpUtil();
        $result = null;
        $length = sizeof($ip);
        if ($length == 1) {
            $ip = trim($ip[0]);
            $result = $ipUtil->getAddressByIp($ip);
        } else {
            foreach ($ip as $item) {
                $item = trim($item);
                $ipUtil->getAddressByIp($item);
//                sleep(1);
            }
            $result = "ok";
        }
        return $this->res($result);
    }

}