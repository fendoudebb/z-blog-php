<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class PostTopicAdd extends Base {

    public function addPostTopic() {
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

        $postTopicCmd = [
            'find' => 'post',
            'filter' => [
                '_id' => new ObjectId($postId)
            ],
            'projection' => [
                '_id' => 0,
                'topics' => 1
            ],
            'limit' => 1
        ];
        $postTopicArr = Mongo::cmd($postTopicCmd);
        if (!empty($postTopicArr)) {
            if (!empty($postTopicArr[0])) {
                $topics = $postTopicArr[0]->topics;
                if (in_array($topic, $topics)) {
                    $this->log(ResCode::POST_TOPIC_ALREADY_EXIST);
                    return $this->fail(ResCode::POST_TOPIC_ALREADY_EXIST);
                }
            }
        }
        $postTopicAddCmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($postId)
                    ],
                    'u' => [
                        '$addToSet' => [
                            'topics' => [
                                '$each' => [
                                    $topic
                                ]
                            ]
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]

        ];
        Mongo::cmd($postTopicAddCmd);
        return $this->res();

    }

}