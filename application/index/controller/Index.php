<?php

namespace app\index\controller;

class Index extends Base {

    public function index() {
        $arr = [
            'title' => '麦司机的个人博客',
            'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，麦司机',
            'description' => 'Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术博客',
            'now' => time(),
            'ip' => $this->ip,
            'currentPage' => 4,
            'pageSize' => 20,
            'totalPage' => 10,
            'post' => [
                [
                    "nickname" => "fendoudebb",
                    "postId" => 19,
                    "postTime" => "2018-09-22 14:28:09",
                    "title" => "上传文件出现413错误(Request Entity Too Large)",
                    "keywords" => "Nginx,上传文件限制",
                    "description" => "Nginx上传文件限制大小",
                    "isCommentClose" => 0,
                    "isCopy" => 0,
                    "originalLink" => "",
                    "isTop" => 0,
                    "pv" => 0,
                    "commentCount" => 0,
                    "likeCount" => 0
                ]
            ]
        ];
        return compressHtml($this->fetch('index', $arr));
    }

}
