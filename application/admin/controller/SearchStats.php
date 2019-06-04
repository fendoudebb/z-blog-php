<?php

namespace app\admin\controller;


use app\common\util\Mongo;

class SearchStats extends BaseRoleNormal {

    public function searchStats() {
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
            'aggregate' => 'search_stats', // collection表名
            'pipeline' => [
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'keywords' => 1,
                        'took' => 1,
                        'hits' => 1,
                        'createTime' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:%M:%S",
                                'date' => [
                                    '$toDate' => '$createTime'
                                ],
                                'timezone' => "+08:00"
                            ]
                        ],
                        'referer' => 1,
                        'ip' => 1,
                        'browser' => 1,
                        'os' => 1

                    ],
                ],
                [
                    '$sort' => ['id' => -1]
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
        $searchStats = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'search_stats'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['searchStats'] = $searchStats;
        return $this->res($response);
    }

}