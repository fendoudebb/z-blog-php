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
        if (empty($ipInfo)) {
            $createTime = new UTCDateTime();
            $address = $this->queryTaobaoIp($ip);
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
        } else {
            $ipAddress = $ipInfo[0];
            if (!property_exists($ipAddress, 'address')) {
                $address = $this->queryTaobaoIp($ip);
                if ($address != null) {
                    $updateIpPoolCmd = [
                        'update' => 'ip_pool',
                        'updates' => [
                            [
                                'q' => [
                                    '_id' => new ObjectId($ipAddress->_id)
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
            } else {
                $address = $ipAddress->address;
            }
        }

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

    private function queryTaobaoIp($ip) {
        $address = null;
        try {
            $result = doGet("http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip);
            Log::log("ip-type: " . gettype($result).", value: ".$result);
            $result = json_decode($result);
            if ($result != null && $result->code === 0) {
                $address = $result->data;
            }
        } catch (Exception $e) {
            Log::log("query taobao exception: " . $e);
        }
        return $address;
    }


}