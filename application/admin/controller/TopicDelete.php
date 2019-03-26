<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class TopicDelete extends BaseRoleAdmin {

    public function deleteTopic() {
        $topicId = strval(input('post.topicId'));
        if (strlen($topicId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        $deleteTopicCmd = [
            'delete' => 'topic',
            'deletes' => [
                [
                    'q' => [
                        '_id' => new ObjectId($topicId)
                    ]
                ]
            ]
        ];
        Mongo::cmd($deleteTopicCmd);
        return $this->res();
    }

}