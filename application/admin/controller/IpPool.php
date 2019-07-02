<?php

namespace app\admin\controller;


use app\common\util\Mongo;
use stdClass;

class IpPool extends BaseRoleNormal {

    public function ipPool() {
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
            'aggregate' => 'ip_pool', // collection表名
            'pipeline' => [
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'ip' => 1,
                        'address' => 1,
                        'createTime' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:%M:%S",
                                'date' => [
                                    '$toDate' => '$createTime'
                                ],
                                'timezone' => "+08:00"
                            ]
                        ],
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
            'cursor' => new stdClass()
        ];
        $ipPool = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'ip_pool'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['ipPool'] = $ipPool;
        return $this->res($response);
    }

    public function unrecognizedIp() {
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
            'aggregate' => 'ip_pool', // collection表名
            'pipeline' => [
                [
                    '$match' => [
                        'address' => [
                            '$exists' => false
                        ]
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'ip' => 1,
                        'createTime' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:%M:%S",
                                'date' => [
                                    '$toDate' => '$createTime'
                                ],
                                'timezone' => "+08:00"
                            ]
                        ],
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
            'cursor' => new stdClass()
        ];
        $ipPool = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'ip_pool',
            'query' => [
                'address' => [
                    '$exists' => false
                ]
            ]
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['unrecognizedIp'] = $ipPool;
        return $this->res($response);
    }

}