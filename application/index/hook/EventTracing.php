<?php

namespace app\index\hook;


use app\common\util\IpUtil;
use app\common\util\Mongo;
use MongoDB\BSON\UTCDateTime;
use think\Log;
use think\Request;

class EventTracing {

    public function responseEnd() {
        $request = Request::instance();
        $url = $request->url();
        $ip = $request->ip();
        $userAgent = $request->header('user-agent');
        $referer = $request->header('referer');
        if (strpos($url, '/404') === 0) {
            return;
        }
        if (strpos($url, '/admin/') === 0) {
            return;
        }

        $address = (new IpUtil())->getAddressByIp($ip);

        $createTime = new UTCDateTime();
        $document = [
            'url' => $url,
            'ip' => $ip,
            'userAgent' => $userAgent,
            'createTime' => $createTime
        ];
        if ($address != null) {
            $document['address'] = $address;
        }
        if (ini_get("browscap")) {
            $userAgentParseResult = get_browser($userAgent, true);
            $document['browser'] = $userAgentParseResult['parent'];
            $document['os'] = $userAgentParseResult['platform'];
        }
        if (isset($referer)) {
            $document['referer'] = $referer;
        }
        if (strpos($url, '/search/') === 0) {
            $search = $request->route('q');
            $took = $request->__get("took");
            $hits = $request->__get("hits");
            $searchStats = [
                'keywords' => $search,
                'createTime' => $createTime,
                'took' => $took,
                'hits' => $hits,
                'ip' => $ip,
            ];
            if ($address != null) {
                $searchStats['address'] = $address;
            }
            if (isset($referer)) {
                $searchStats['referer'] = $referer;
            }
            if (isset($userAgentParseResult)) {
                $searchStats['browser'] = $userAgentParseResult['parent'];
                $searchStats['os'] = $userAgentParseResult['platform'];
            }
            $insertSearchStatsCmd = [
                'insert' => 'search_stats',
                'documents' => [
                    $searchStats
                ]
            ];
            Mongo::cmd($insertSearchStatsCmd);
        }
        $insertPageViewRecordCmd = [
            'insert' => 'page_view_record',
            'documents' => [
                $document
            ]
        ];
        Mongo::cmd($insertPageViewRecordCmd);
        /*$pipeline = Redis::init()->multi(\Redis::PIPELINE);
        $pipeline->pfAdd(RedisKey::HYPER_IP, [$ip]);
        $pipeline->incrBy(RedisKey::STR_PV, 1);
        $result = $pipeline->exec();
        $newIp = $result[0];
        if ($newIp) {
            Mongo::cmd([
                'insert' => 'ip_pool',
                'documents' => [
                    [
                        'ip' => $ip,
                        'createTime' => $createTime
                    ]
                ]
            ]);
        }*/
        if (strpos($url, '/p/') === 0) {
            $postId = $request->route('postId');
            if (!isset($postId)) {
                Log::log('event tracing post id is empty');
                return;
            }
            Mongo::cmd([
                'update' => 'post',
                'updates' => [
                    [
                        'q' => [
                            'postId' => intval($postId)
                        ],
                        'u' => [
                            '$inc' => [
                                'pv' => 1
                            ],
                            '$currentDate' => [
                                'lastModified' => true
                            ],
                        ]
                    ]
                ]
            ]);
        }
        $memory_use = number_format((memory_get_usage() - THINK_START_MEM) / 1024 / 1024, 2);
        $date = date('Y-m-d H:i:s', time());
        $param = $request->param();
        Log::log("[$date] : ip[$ip], url[$url], memory[$memory_use mb], request param -> " . json_encode($param));
    }
}