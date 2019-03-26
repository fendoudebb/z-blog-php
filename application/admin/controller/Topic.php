<?php

namespace app\admin\controller;


use app\common\util\Mongo;

class Topic extends BaseRoleAdmin {

    public function topic() {
        $page = intval(input('post.page'));
        $size = intval(input('post.size'));
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        $offset = ($page - 1) * $size;

        $cmd = [
            'aggregate' => 'topic', // collection表名
            'pipeline' => [
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'name' => 1,
                        'sort' => 1,
                    ],
                ],
                [
                    '$sort' => ['sort' => 1]
                ],
                [
                    '$skip' => $offset
                ],
                [
                    '$limit' => $size
                ]
            ],
            'cursor' => new \stdClass()
        ];
        $topic = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'topic'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['topic'] = $topic;
        return $this->res($response);
    }

}