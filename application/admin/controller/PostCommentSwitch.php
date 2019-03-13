<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;
use think\Db;

class PostCommentSwitch extends BaseRoleAdmin {

    public function switchPostComment() {
        $postId = input('post.postId');
        $commentStatus = strval(input('post.commentStatus'));
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        $updatePostCommentStatus = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($postId)
                    ],
                    'u' => [
                        '$set' => [
                            'commentStatus' => $commentStatus
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]
        ];
        $updatePostCommentStatusResult = Mongo::cmd($updatePostCommentStatus);
        if (empty($updatePostCommentStatusResult) || !$updatePostCommentStatusResult[0]->ok) {
            $this->log(ResCode::COLLECTION_UPDATE_FAIL);
            return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
        }
        return $this->res();
    }

}