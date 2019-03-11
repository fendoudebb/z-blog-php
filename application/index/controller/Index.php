<?php

namespace app\index\controller;

use think\Db;

class Index extends Base {

    public function index() {
        $page = intval(input('get.page'));
        if ($page < 1) {
            $page = 1;
        }
        $size = 20;
        $offset = ($page - 1) * $size;
        $arr = [
            'title' => 'Z-Blog',
            'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，Z-Blog',
            'description' => 'Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术博客',
            'currentPage' => $page,
        ];
        if (!$this->isMobile) {
            $pvRankCmd = [
                'find' => 'post',
                'filter' => [
                    'status' => 1,
                    'isPrivate' => false,
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1,
                    'pv' => 1
                ],
                'sort' => [
                    'pv' => -1
                ],
                'limit' => 5
            ];
            $pvRankCmdArr = Db::cmd($pvRankCmd);
            if (!empty($pvRankCmdArr)) {
                $arr['pvRank'] = $pvRankCmdArr[0];
            }

            $likeCountRankCmd = [
                'find' => 'post',
                'filter' => [
                    'status' => 1,
                    'isPrivate' => false,
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1,
                    'pv' => 1
                ],
                'sort' => [
                    'likeCount' => -1
                ],
                'limit' => 5
            ];
            $likeCountRankCmdArr = Db::cmd($likeCountRankCmd);
            if (!empty($likeCountRankCmdArr)) {
                $arr['commentRank'] = $likeCountRankCmdArr[0];
            }

            $commentCountRankCmd = [
                'find' => 'post',
                'filter' => [
                    'status' => 1,
                    'isPrivate' => false,
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1,
                    'pv' => 1
                ],
                'sort' => [
                    'likeCount' => -1
                ],
                'limit' => 5
            ];
            $commentCountRankCmdArr = Db::cmd($commentCountRankCmd);
            if (!empty($commentCountRankCmdArr)) {
                $arr['likeRank'] = $commentCountRankCmdArr[0];
            }
        }

        $indexPostsCmd = [
            'find' => 'post',
            'filter' => [
                'status' => 1,
                'isPrivate' => false,
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1,
                'postTime' => 1,
                'title' => 1,
                'isCopy' => 1,
                'description' => 1,
                'pv' => 1,
                'likeCount' => 1,
                'commentCount' => 1,
                'topics' => 1
            ],
            'sort' => [
                'postTime' => -1
            ],
            'skip' => $offset,
            'limit' => $size
        ];
        $indexPostsCmdArr = Db::cmd($indexPostsCmd);
        $arr['posts'] = $indexPostsCmdArr;
        $countPostsCmd = [
            'count' => 'post',
            'query' => [
                'status' => 1,
                'isPrivate' => false
            ]
        ];
        $countPostCmdArr = Db::cmd($countPostsCmd);
        $arr['totalPage'] = ceil($countPostCmdArr[0]['n'] / $size);
        $compressHtml = compressHtml($this->fetch('index', $arr));
        return $compressHtml;
    }

}
