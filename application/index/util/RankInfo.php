<?php

namespace app\index\util;


use app\common\util\Mongo;
use think\Request;

class RankInfo {
    public function rankInfo() {
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
                    'pv' => -1
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
                    'likeCount' => -1
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
                    'commentCount' => -1
                ],
                'limit' => 5
            ];
            $arr['commentRank'] = Mongo::cmd($commentCountRankCmd);
        }
        return $arr;
    }
}