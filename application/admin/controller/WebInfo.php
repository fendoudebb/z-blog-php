<?php

namespace app\admin\controller;


use app\common\config\RedisKey;
use app\common\util\Mongo;
use app\common\util\Redis;

class WebInfo extends BaseRoleAdmin {

    public function webInfo() {
        $arr = [];
        $webInfo = Redis::init()->get(RedisKey::WEB_INFO);
        if ($webInfo) {
            $arr = unserialize($webInfo);
        } else {
            $ipCountCmd = [
                'count' => 'ip_pool',
            ];
            $pvCountCmd = [
                'count' => 'page_view_record',
            ];
            $postCountCmd = [
                'count' => 'post',
                'query' => [
                    'postStatus' => 'ONLINE',
                ]
            ];
            $ipCountCmdArr = Mongo::cmd($ipCountCmd);
            $ipCount = $ipCountCmdArr[0]->n;
            $pvCountCmdArr = Mongo::cmd($pvCountCmd);
            $pvCount = $pvCountCmdArr[0]->n;
            $postCountCmdArr = Mongo::cmd($postCountCmd);
            $postCount = $postCountCmdArr[0]->n;

            $arr['webInfo'] = [
                'ipCount' => $ipCount,
                'pvCount' => $pvCount,
                'postCount' => $postCount,
            ];
            Redis::init()->setex(RedisKey::WEB_INFO, 3600, serialize($arr));
        }
        return $this->res($arr);
    }


}