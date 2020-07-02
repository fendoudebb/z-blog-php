<?php


namespace app\common\util;


use Exception;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\Log;

/*
{
    "code": 0,
    "data": {
        "ip": "112.65.145.179",
        "country": "中国",
        "area": "",
        "region": "上海",
        "city": "上海",
        "county": "XX",
        "isp": "联通",
        "country_id": "CN",
        "area_id": "",
        "region_id": "310000",
        "city_id": "310100",
        "county_id": "xx",
        "isp_id": "100026"
    }
}
*/

class IpUtil {

    public function getAddressByIp($ip) {
        $address = null;
        $ipInfo = $this->findIpInfo($ip);
        if (empty($ipInfo)) {
            $address = $this->queryTaobaoIp($ip);
            $this->insertIpInfo($ip, $address);
        } else {
            $ipAddress = $ipInfo[0];
            if (!property_exists($ipAddress, 'address')) {
                $address = $this->queryTaobaoIp($ip);
                if ($address != null) {
                    $this->updateIpAddress($ipAddress->_id, $address);
                }
            } else {
                $address = $ipAddress->address;
            }
        }
        return $this->parseAddress($address);
    }

    public function getAddressByResult($result) {
        Log::log("get address by result#$result");
        $address = $this->decodeResult($result);
        if ($address == null) {
            return null;
        }
        $ip = $address->ip;
        $ipInfo = $this->findIpInfo($ip);
        if (empty($ipInfo)) {
            $this->insertIpInfo($ip, $address);
        } else {
            $ipAddress = $ipInfo[0];
            if (!property_exists($ipAddress, 'address')) {
                $this->updateIpAddress($ipAddress->_id, $address);
            } else {
                $address = $ipAddress->address;
            }
        }
        return $this->parseAddress($address);
    }

    private function findIpInfo($ip) {
        $cmd = [
            'find' => 'ip_pool',
            'filter' => [
                'ip' => $ip,
            ],
            'projection' => [
                'address' => 1
            ],
            'limit' => 1
        ];
        $ipInfo = Mongo::cmd($cmd);
        return $ipInfo;
    }

    private function insertIpInfo($ip, $address) {
        $createTime = new UTCDateTime();
        $document = [
            'ip' => $ip,
            'createTime' => $createTime
        ];
        if ($address != null) {
            $document['address'] = $address;
        }
        $insertIpPoolCmd = [
            'insert' => 'ip_pool',
            'documents' => [
                $document
            ]
        ];
        Mongo::cmd($insertIpPoolCmd);
    }

    private function updateIpAddress($id, $address) {
        $updateIpPoolCmd = [
            'update' => 'ip_pool',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($id)
                    ],
                    'u' => [
                        '$set' => [
                            'address' => $address
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]
        ];
        Mongo::cmd($updateIpPoolCmd);
    }

    private function queryTaobaoIp($ip) {
        $address = null;
        try {
            $result = doGet("http://ip.taobao.com/getIpInfo.php?ip=" . $ip);
            Log::log($ip.": ip-type: " . gettype($result) . ", value: " . $result);
            $address = $this->decodeResult($result);
        } catch (Exception $e) {
            Log::log("query taobao exception: " . $e);
        }
        return $address;
    }

    private function decodeResult2($result) {
        $address = null;
        $result = json_decode($result);
        if ($result != null && $result->code === 0) {
            $address = $result->data;
        }
        return $address;

    }

    private function decodeResult($result) {
        $address = null;
        $result = json_decode($result);
        if ($result != null && $result->code === "0") {
            $address = $result->data;
            $address->ip = $address->QUERY_IP;//ip
            $address->country = $address->COUNTRY_CN;//国家
            $address->country_id = $address->COUNTRY_CODE;//国家id
            $address->area = $address->AREA_CN;//地区
            $address->area_id = $address->AREA_CODE;//地区id
            $address->region = $address->PROVINCE_CN;//省份
            $address->region_id = $address->PROVINCE_CODE;//省份id
            $address->city = $address->CITY_CN;//城市
            $address->city_id = $address->CITY_CODE;//城市id
            $address->county = $address->COUNTY_CN;//县
            $address->county_id = $address->COUNTY_CODE;//县
            $address->isp = $address->ISP_CN;//ISP
            $address->isp_id = $address->ISP_CODE;//ISP id
        }
        return $address;

    }

    private function parseAddress($address) {
        if ($address != null) {
            $country = $address->country;//国家
            $area = $address->area;//地区
            $region = $address->region;//省份
            $city = $address->city;//城市
            $county = $address->county;//县
            $isp = $address->isp;//运营商
            $address = $country . $area . (($region === 'XX') ? '' : $region) . (($city === $region || $city === 'XX') ? '' : $city) . (($county === 'XX') ? '' : $county) . ($isp === 'XX' ? '' : $isp);
        }
        return $address;
    }


}