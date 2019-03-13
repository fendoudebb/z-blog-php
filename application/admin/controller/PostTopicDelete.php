<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;
use think\Db;

class PostTopicDelete extends BaseRoleAdmin {

    public function deletePostTopic() {
        $postId = input('post.postId');
        $topic = input('post.topic');
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (!isset($topic)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC);
        }
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if (empty($topic)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC);
        }
        $deletePostTopicCmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($postId)
                    ],
                    'u' => [
                        '$pull' => [
                            'topics' => $topic
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]
        ];
        Mongo::cmd($deletePostTopicCmd);
        return $this->res();
    }

}