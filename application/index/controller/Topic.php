<?php

namespace app\index\controller;


use app\common\util\Mongo;
use app\index\util\SidebarInfo;

class Topic extends Base {

    public function topic($topic) {
        $page = intval(input('get.page'));
        if ($page < 1) {
            $page = 1;
        }
        $size = 20;
        $offset = ($page - 1) * $size;
        $arr = [
            'title' => $topic,
            'keywords' => $topic,
            'description' => $topic,
            'currentPage' => $page,
        ];
        $rankInfo = new SidebarInfo();
        $arr = array_merge($arr, $rankInfo->sidebarInfo());
        $indexPostsCmd = [
            'find' => 'post',
            'filter' => [
                'postStatus' => 'ONLINE',
                'topics' => $topic
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1,
                'postTime' => 1,
                'title' => 1,
                'postProp' => 1,
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
        $indexPostsCmdArr = Mongo::cmd($indexPostsCmd);
        $arr['posts'] = $indexPostsCmdArr;
        $countPostsCmd = [
            'count' => 'post',
            'query' => [
                'postStatus' => 'ONLINE',
                'topics' => $topic
            ]
        ];
        $countPostCmdArr = Mongo::cmd($countPostsCmd);
        $arr['totalPage'] = ceil($countPostCmdArr[0]->n / $size);
        $compressHtml = compressHtml($this->fetch('topic', $arr));
        return $compressHtml;
    }

}