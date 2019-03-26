<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;

class TopicAdd extends BaseRoleAdmin {

    public function addTopic() {
        $topic = strval(input('post.topic'));
        $findMaxSortTopicCmd = [
            'find' => 'post',
            'sort' => [
                'sort' => -1
            ],
            'projection' => [
                '_id' => 0,
                'sort' => 1
            ],
            'limit' => 1,
        ];
        $cmdArr = Mongo::cmd($findMaxSortTopicCmd);
        if (empty($cmdArr)) {
            $sort = 1;
        } else {
            if (property_exists($cmdArr[0],'sort')) {
                $sort = $cmdArr[0]->sort + 1;
            } else {
                $sort = 1;
            }
        }

        $insertTopicCmd = [
            'insert' => 'post',
            'documents' => [
                [
                    'topic' => $topic,
                    'sort' => $sort,
                ]
            ]
        ];
        $insertTopicResult = Mongo::cmd($insertTopicCmd);
        if (empty($insertTopicResult) || !$insertTopicResult[0]->ok) {
            $this->log(ResCode::COLLECTION_INSERT_FAIL);
            return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
        }
        return $this->res();
    }

}