<?php

namespace app\admin\controller;


use app\common\util\Mongo;

class PageView extends BaseAuth {

    public function pageView() {
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
            'aggregate' => 'page_view_record', // collection表名
            'pipeline' => [
                [
                    '$project' => [
                        '_id' => 0,
                        'id' => [
                            '$toString' => '$_id'
                        ],
                        'url' => 1,
                        'createTime' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:%M:%S",
                                'date' => [
                                    '$toDate' => '$createTime'
                                ],
                                'timezone' => "+08:00"
                            ]
                        ],
                        'ip' => 1,
                        'browser' => 1,
                        'os' => 1,
                        'referer' => 1,
                        'userAgent' => 1,
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
        $pageView = Mongo::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'page_view_record'
        ];
        $countResult = Mongo::cmd($cmd);
        $response['totalCount'] = $countResult[0]->n;
        $response['pageView'] = $pageView;
        return $this->res($response);
    }

}