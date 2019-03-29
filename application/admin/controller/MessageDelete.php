<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class MessageDelete extends BaseRoleAdmin {

    public function deleteMessage() {
        $commentId = strval(input('post.commentId'));
        if (strlen($commentId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
        }


        $cmd = [
            'update' => 'comment',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($commentId),
                    ],
                    'u' => [
                        '$set' => [
                            'status' => 'OFFLINE'
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