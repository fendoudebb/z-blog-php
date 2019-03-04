<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use MongoDB\BSON\ObjectId;
use think\Db;

class PostComments extends BaseRoleAdmin {

    public function postComments() {
        $postId = input('post.postId');
        $page = intval(input('post.page'));
        $size = intval(input('post.size'));
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        //https://docs.mongodb.com/manual/reference/operator/projection/slice/#proj._S_slice
        $postCommentCmd = [
            'find' => 'post',
            'filter' => [
                '_id' => new ObjectId($postId),
            ],
            'projection' => [
                'comments' => [
                    '$slice' => [$page, $size]
                ]
            ],
            'limit' => 1,
        ];
        $postCommentCmdArr = Db::cmd($postCommentCmd);
        return $this->res($postCommentCmdArr);
    }

}