<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use DateTimeZone;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class PostComment extends BaseRoleAdmin {

    public function postComment() {
        $postId = input('post.postId');
        $page = intval(input('post.page'));
        $size = intval(input('post.size'));
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if ($page < 1) {
            $page = 1;
        }
        if ($size < 1 || $size > 20) {
            $size = 20;
        }
        //https://docs.mongodb.com/manual/reference/operator/projection/slice/#proj._S_slice
        $postOid = new ObjectId($postId);
        $postCommentCmd = [
            'find' => 'post',
            'filter' => [
                '_id' => $postOid,
            ],
            'projection' => [
                '_id' => 0,
                'commentCount' => 1,
                'postComment' => [
                    '$slice' => [$page - 1, $size]
                ]
            ],
            'limit' => 1,
        ];
        /*$postCommentCountCmd = [
            'aggregate' => 'post', // collection表名
            'pipeline' => [
                [
                    '$match' => [
                        '_id' => $postOid
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'commentCount' => [
                            '$cond' => [
                                'if' => [
                                    '$isArray' => '$postComment'
                                ],
                                'then' => [
                                    '$size' => '$postComment'
                                ],
                                'else' => 0
                            ]
                        ]
                    ],
                ],
                [
                    '$limit' => $size
                ]
            ],
            'cursor' => new \stdClass()
        ];*/
        $postCommentCmdArr = Mongo::cmd($postCommentCmd);
        if (empty($postCommentCmdArr)) {
            $this->log(ResCode::POST_DOES_NOT_EXIST);
            return $this->fail(ResCode::POST_DOES_NOT_EXIST);
        }
        $data = $postCommentCmdArr[0];
        $commentCount = $data->commentCount;
        if ($commentCount <= 0) {
            $this->log(ResCode::POST_COMMENT_IS_EMPTY);
            return $this->fail(ResCode::POST_COMMENT_IS_EMPTY);
        }
        foreach ($data->postComment as $comment) {
            $commentTime = $comment->commentTime;
            if ($commentTime instanceof UTCDateTime) {
                $dateTime = $commentTime->toDateTime();
                $dateTime->setTimezone(new DateTimeZone("Asia/Shanghai"));//date_default_timezone_get()
                $commentTime = $dateTime->format("Y-m-d H:i:s");
            }
            $comment->commentId = $comment->commentId->__toString();
            $comment->commentTime = $commentTime;

            //TODO 管理界面时间格式化
        }
        return $this->res($data);
    }

}