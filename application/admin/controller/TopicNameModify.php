<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class TopicNameModify extends BaseRoleAdmin {

    public function modifyTopicName() {
        $topicId = strval(input('post.topicId'));
        $name = strval(input('post.name'));
        if (strlen($topicId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        if (strlen($name) <= 0) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_NAME);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_NAME);
        }

        $cmd = [
            'update' => 'topic',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($topicId),
                    ],
                    'u' => [
                        '$set' => [
                            'name' => $name
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