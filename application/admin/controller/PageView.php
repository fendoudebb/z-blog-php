<?php

namespace app\admin\controller;


use app\common\util\Mongo;
use DateTimeZone;
use MongoDB\BSON\UTCDateTime;

class PageView extends Base {

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
        /*$cmd = [
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
                        'address' => 1
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
        ];*/

        $cmd = [
            'find' => 'page_view_record',
            'projection' => [
                '_id' => 1,
                'url' => 1,
                'createTime' => 1,
                'ip' => 1,
                'browser' => 1,
                'os' => 1,
                'referer' => 1,
                'userAgent' => 1,
                'address' => 1
            ],
            'sort' => [
                '_id' => -1
            ],
            'skip' => $offset
            ,
            'limit' => $size

        ];

        $pageView = Mongo::cmd($cmd);

        foreach ($pageView as $pv) {
            $pv->_id = $pv->_id->__toString();
            if ($pv->createTime instanceof UTCDateTime) {
                $dateTime = $pv->createTime->toDateTime();
                $dateTime->setTimezone(new DateTimeZone("Asia/Shanghai"));//date_default_timezone_get()
                $pv->createTime = $dateTime->format("Y-m-d H:i:s");
            }
        }

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