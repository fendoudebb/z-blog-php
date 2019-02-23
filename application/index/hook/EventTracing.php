<?php

namespace app\index\hook;


use app\common\config\RedisKey;
use app\common\util\Redis;
use think\Db;
use think\Exception;
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
        $record = [
            'url' => $url,
            'ip' => $ip,
            'user_agent' => $userAgent,
        ];
        if (isset($referer)) {
            $record['referer'] = $referer;
        }
        Db::table('page_view_record')
            ->insert($record);
        $pipeline = Redis::init()->multi(\Redis::PIPELINE);
        $pipeline->pfAdd(RedisKey::HYPER_IP, [$ip]);
        $pipeline->incrBy(RedisKey::STR_PV, 1);
        $result = $pipeline->exec();
        $newIp = $result[0];
        if ($newIp) {
            Db::table('ip_pool')
                ->insert([
                    'ip' => $ip
                ]);
        }
        $memory_use = number_format((memory_get_usage() - THINK_START_MEM) / 1024 / 1024, 2);
        $date = date('Y-m-d H:i:s', time());
        $param = $request->param();
        Log::log("[$date] : ip[$ip], url[$url], memory[$memory_use mb], request param -> ". json_encode($param));

        if (strpos($url, '/p/') === 0) {
            $postId = $request->route('postId');
            if (!isset($postId)) {
                Log::log('event tracing post id is empty');
                return;
            }
            try {
                $updatePostResult = Db::table('post')
                    ->where('id', $postId)
                    ->update([
                        'pv' => Db::raw('pv + 1')
                    ]);
                if (!$updatePostResult) {
                    Log::log('event tracing update post fail');
                    return;
                }
                Redis::init()->hIncrBy(RedisKey::HASH_POST_DETAIL . $postId, RedisKey::POST_PV, 1);
            } catch (Exception $e) {
                Log::log("event tracing log exception->" . $e->getMessage());
            }
        }

    }

}