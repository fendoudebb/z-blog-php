<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class PostCommentDelete extends BaseRoleAdmin {

    public function deletePostComment() {
        $postId = strval(input('post.postId'));
        $commentId = strval(input('post.commentId'));
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if (strlen($commentId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
        }
        $cmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($postId),
                        'postComment.commentId' => new ObjectId($commentId)
                    ],
                    'u' => [
                        '$set' => [
                            'postComment.$.status' => 'OFFLINE'
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ],
        ];
        $modifyResult = Mongo::cmd($cmd);
        if (empty($modifyResult) || !$modifyResult[0]->ok) {
            $this->log(ResCode::COLLECTION_UPDATE_FAIL);
            return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
        }
        return $this->res();
    }

}