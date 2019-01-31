<?php

namespace app\index\controller;

use app\common\config\RedisKey;
use app\common\util\Redis;
use think\Controller;
use think\Db;
use think\Log;
use think\Request;

abstract class Base extends Controller {

    protected $ip;
    protected $url;

    public function _initialize() {
        $request = Request::instance();
        $date = date('Y-m-d H:i:s', time());
        $this->ip = $request->ip();
        $this->url = $request->url();
        $userAgent = $this->request->header('user-agent');
        $referer = $this->request->header('referer');
        if (!isset($referer)) {
            $referer = '';
        }
        $memory_use = number_format((memory_get_usage() - THINK_START_MEM) / 1024 / 1024, 2);
        $param = $request->param();
        Log::log("[$date] : ip[$this->ip], url[$this->url], referer[$referer], user-agent[$userAgent], memory[$memory_use mb], request param -> ". json_encode($param));
        Db::table('page_view_record')
            ->insert([
                'url' => $this->url,
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
    }

    public function log($msg) {
        Log::log("[$this->url]-[$this->ip]-> " . $msg);
    }

    public function logException($errorMsg) {
        Log::log("[$this->url]-[$this->ip]-> $errorMsg");
    }

}

