<?php

namespace app\mobile\controller;


use app\admin\controller\Base;
use app\common\util\Mongo;

class MobileIndex extends Base {

    function index() {
        $page = intval(input('get.page'));
        $size = intval(input('get.size'));
        $topic = strval(input('get.topic'));
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;
        $arr = [
            'currentPage' => $page,
        ];
        $indexPostsCmd = [
            'find' => 'post',
            'filter' => [
                'postStatus' => 'ONLINE',
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1,
                'postTime' => 1,
                'title' => 1,
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

        $countPostsCmd = [
            'count' => 'post',
            'query' => [
                'postStatus' => 'ONLINE',
            ]
        ];

        if (!empty($topic) && $topic !== '全部') {
            $indexPostsCmd['filter'] = [
                'topics' => $topic
            ];
            $countPostsCmd['query'] = [
                'topics' => $topic
            ];
        }
        $indexPostsCmdArr = Mongo::cmd($indexPostsCmd);
        $arr['posts'] = $indexPostsCmdArr;

        $countPostCmdArr = Mongo::cmd($countPostsCmd);
        $arr['totalPage'] = ceil($countPostCmdArr[0]->n / $size);
        return $this->res($arr);
    }

}