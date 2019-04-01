<?php

namespace app\index\util;


use app\common\config\RedisKey;
use app\common\util\Mongo;
use app\common\util\Redis;
use think\Request;

class SidebarInfo {
    public function sidebarInfo() {
        $arr = [];
        if (!Request::instance()->isMobile()) {
            $sideBarInfo = Redis::init()->get(RedisKey::SIDEBAR_INFO);
            if ($sideBarInfo) {
                return json_decode($sideBarInfo);
            }
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

            $arr['webmaster'] = [
                'ipCount' => $ipCount,
                'pvCount' => $pvCount,
                'postCount' => $postCount,
            ];
            $pvRankCmd = [
                'find' => 'post',
                'filter' => [
                    'postStatus' => 'ONLINE',
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1,
                    'title' => 1,
                    'pv' => 1
                ],
                'sort' => [
                    'pv' => -1,
                    'postTime' => -1
                ],
                'limit' => 5
            ];
            $arr['pvRank'] = Mongo::cmd($pvRankCmd);

            $likeCountRankCmd = [
                'find' => 'post',
                'filter' => [
                    'postStatus' => 'ONLINE',
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1,
                    'title' => 1,
                    'likeCount' => 1
                ],
                'sort' => [
                    'likeCount' => -1,
                    'postTime' => -1
                ],
                'limit' => 5
            ];
            $arr['likeRank'] = Mongo::cmd($likeCountRankCmd);

            $commentCountRankCmd = [
                'find' => 'post',
                'filter' => [
                    'postStatus' => 'ONLINE',
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1,
                    'title' => 1,
                    'commentCount' => 1
                ],
                'sort' => [
                    'commentCount' => -1,
                    'postTime' => -1
                ],
                'limit' => 5
            ];
            $arr['commentRank'] = Mongo::cmd($commentCountRankCmd);

            $topicCmd = [
                'find' => 'topic',
                'projection' => [
                    '_id' => 0,
                    'name' => 1,
                ],
                'sort' => [
                    'sort' => 1
                ]
            ];

            $arr['topic'] = Mongo::cmd($topicCmd);
            Redis::init()->setex(RedisKey::SIDEBAR_INFO, 3600, json_encode($arr));
        }
        return $arr;
    }
}