<?php

namespace app\index\controller;

use app\index\util\RankInfo;
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
            'title' => 'Z-博客',
            'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，Z-博客',
            'description' => 'Z-博客：记录Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术',
            'currentPage' => $page,
        ];
        $rankInfo = new RankInfo();
        $arr = array_merge($arr, $rankInfo->rankInfo());
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
