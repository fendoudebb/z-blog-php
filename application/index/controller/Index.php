<?php

namespace app\index\controller;

use think\Db;

class Index extends Base {

    public function index() {
        $page = input('get.page');
        $size = input('get.size');
        if (!isset($page) || !is_numeric($page) || $page < 1) {
            $page = 1;
        }
        if (!isset($size) || !is_numeric($size) || $size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;

        $post = Db::query("SELECT p.id AS postId, DATE_FORMAT(p.post_time, '%Y-%m-%d') AS postTime, p.status, p.title, p.description, p.is_comment_close AS isCommentClose, p.is_private AS isPrivate, 
p.is_copy AS isCopy, p.original_link AS originalLink, p.is_top AS isTop, p.pv, p.comment_count AS commentCount, p.like_count AS likeCount 
                                FROM `post` p INNER JOIN 
                                (SELECT id FROM `post` WHERE STATUS = 1 and is_private = 0 ORDER BY `post_time` DESC LIMIT $offset, $size) b USING (id)");
        $arr = [
            'title' => '麦司机的个人博客',
            'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，麦司机',
            'description' => 'Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术博客',
            'now' => time(),
            'ip' => $this->ip,
            'currentPage' => $page,
            'pageSize' => $size,
            'totalPage' => 10,
            'post' => $post
        ];
        return compressHtml($this->fetch('index', $arr));
    }

}
