<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;

class PostCommentSwitch extends BaseRoleAdmin {

    public function switchPostComment() {
        $postId = input('post.postId');
        $commentStatus = boolval(input('post.commentStatus'));
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        $updatePostCommentStatus = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        '_id' => new \MongoDB\BSON\ObjectId($postId)
                    ],
                    'u' => [
                        '$set' => [
                            'isCommentClose' => $commentStatus
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]
        ];
        $updatePostCommentStatusResult = Db::cmd($updatePostCommentStatus);
        if (empty($updatePostCommentStatusResult) || !$updatePostCommentStatusResult[0]['ok']) {
            $this->log(ResCode::COLLECTION_UPDATE_FAIL);
            return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
        }
        return $this->res();
    }

}