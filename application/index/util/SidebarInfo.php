<?php

namespace app\index\util;


use app\common\config\RedisKey;
use app\common\util\Mongo;
use app\common\util\Redis;

class SidebarInfo {
    public function sidebarInfo() {
        $arr = [];
        $webInfo = Redis::init()->get(RedisKey::WEB_INFO);
        if ($webInfo) {
            return unserialize($webInfo);
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
        $linksCountCmd = [
            'count' => 'links',
            'query' => [
                'status' => 'ONLINE',
            ]
        ];
        $linksCountCmdArr = Mongo::cmd($linksCountCmd);
        $linksCount = $linksCountCmdArr[0]->n;

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
            'linksCount' => $linksCount,
        ];

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

        $linksCmd = [
            'find' => 'links',
            'filter' => [
                'status' => 'ONLINE',
            ],
            'projection' => [
                '_id' => 0,
                'websiteName' => 1,
                'link' => 1,
            ],
            'sort' => [
                'sort' => 1
            ]
        ];

        $arr['links'] = Mongo::cmd($linksCmd);

        Redis::init()->setex(RedisKey::WEB_INFO, 3600, serialize($arr));
        return $arr;
    }
}