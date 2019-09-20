<?php

namespace app\admin\controller;


use app\common\util\Mongo;
use DateTimeZone;
use MongoDB\BSON\UTCDateTime;
use stdClass;

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
                        'replies' => 1,
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
            'cursor' => new stdClass()
        ];
        $comments = Mongo::cmd($cmd);

        foreach ($comments as $comment) {
            if (property_exists($comment, "replies")) {
                foreach ($comment->replies as $reply) {
                    $replyTime = $reply->replyTime;
                    if ($replyTime instanceof UTCDateTime) {
                        $dateTime = $replyTime->toDateTime();
                        $dateTime->setTimezone(new DateTimeZone("Asia/Shanghai"));//date_default_timezone_get()
                        $replyTime = $dateTime->format("Y-m-d H:i:s");
                    }
                    $reply->replyTime = $replyTime;
                }
            }
        }
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