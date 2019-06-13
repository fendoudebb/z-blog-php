<?php

namespace app\admin\controller;


use app\common\util\Mongo;

class MessageBoard extends BaseRoleNormal {

    public function messageBoard() {
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
            'aggregate' => 'comment', // collection表名
            'pipeline' => [
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'nickname' => 1,
                        'content' => 1,
                        'commentTime' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:%M:%S",
                                'date' => [
                                    '$toDate' => '$commentTime'
                                ],
                                'timezone' => "+08:00"
                            ]
                        ],
                        'floor' => 1,
                        'status' => 1,
                        'browser' => 1,
                        'os' => 1,
                        'userAgent' => 1,
                        'ip' => 1,
                        'address' => 1,
                        'reply' => 1,
                    ],
                ],
                [
                    '$sort' => ['floor' => -1]
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
        $comments = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'comment'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['comments'] = $comments;
        return $this->res($response);
    }

}