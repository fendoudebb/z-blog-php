<?php

namespace app\admin\controller;


use think\Db;

class Post extends BaseRoleAdmin {

    public function postList() {
        $page = input('post.page');
        $size = input('post.size');
        if (!isset($page) || !is_numeric($page)) {
            $page = 0;
        }
        if (!isset($size) || !is_numeric($size) || $size >= 20) {
            $size = 20;
        }
        $offset = $page * $size;
        $post = Db::query("SELECT u.nickname, 
p.id AS postId, p.post_time AS postTime, p.status, p.title, p.keywords, p.description, p.is_comment_close AS isCommentClose, p.is_private AS isPrivate, 
p.is_copy AS isCopy, p.original_link AS originalLink, p.is_top AS isTop, p.pv, p.comment_count AS commentCount, p.like_count AS likeCount 
                                FROM `post` p INNER JOIN 
                                (SELECT id FROM `post` ORDER BY `post_time` DESC LIMIT $offset, $size) b USING (id) 
                                STRAIGHT_JOIN  `sys_user` `u` ON `p`.`user_id` = `u`.`id`");
        return $this->res($post);
    }
}