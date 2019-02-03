<?php

namespace app\index\controller;


use app\common\config\RedisKey;
use app\common\util\Redis;
use think\Db;
use think\Log;

class EventTracing extends Base {

    public function traceEvent() {
        $userAgent = $this->request->header('user-agent');
        $url = input('post.url');
        $referer = input('post.referer');
        if (!isset($url)) {
            $this->log('url is empty');
            return 'fail';
        }
        if (!isset($referer)) {
            $referer = '';
        }
        Db::table('page_view_record')
            ->insert([
                'url' => $url,
                'ip' => $this->ip,
                'user_agent' => $userAgent,
                'referer' => $referer
            ]);
        $addIpPool = Redis::init()->pfAdd(RedisKey::HYPER_IP, [$this->ip]);
        if ($addIpPool) {
            Db::table('ip_pool')
                ->insert([
                    'ip' => $this->ip
                ]);
        }
        $memory_use = number_format((memory_get_usage() - THINK_START_MEM) / 1024 / 1024, 2);
        $date = date('Y-m-d H:i:s', time());
        $param = $this->request->param();
        Log::log("[$date] : ip[$this->ip], url[$this->url], memory[$memory_use mb], request param -> ". json_encode($param));

    }

}