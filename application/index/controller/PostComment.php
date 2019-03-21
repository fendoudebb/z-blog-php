<?php

namespace app\index\controller;


class PostComment extends Base {

    public function postComment() {
        $postId = intval(input("post.postId"));
        $postComment = strval(input("post.postComment"));

        return $postId . $postComment;
    }

}