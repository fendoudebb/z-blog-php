<?php

namespace app\admin\controller;

// 网页链接，网站名称，站长名称，站长邮箱

use app\common\util\Mongo;
use stdClass;

class Links extends BaseRoleAdmin {

    public function links() {
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
            'aggregate' => 'links', // collection表名
            'pipeline' => [
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'link' => 1,
                        'websiteName' => 1,
                        'owner' => 1,
                        'ownerEmail' => 1,
                        'sort' => 1,
                        'status' => 1,
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
            'cursor' => new stdClass()
        ];
        $topic = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'links'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['links'] = $topic;
        return $this->res($response);
    }

}