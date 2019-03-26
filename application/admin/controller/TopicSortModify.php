<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class TopicSortModify extends BaseRoleAdmin {

    public function modifyTopicSort() {
        $topicId = strval(input('post.topicId'));
        $sort = intval(input('post.sort'));
        if (strlen($topicId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
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
                            'sort' => $sort
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