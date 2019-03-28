<?php

namespace app\index\util;


use app\common\util\Mongo;
use think\Request;

class SidebarInfo {
    public function sidebarInfo() {
        $arr = [];
        if (!Request::instance()->isMobile()) {
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
        }
        return $arr;
    }
}