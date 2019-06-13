<?php

namespace app\index\controller;


use app\common\util\IpUtil;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class PostComment extends Base {

    public function postComment() {
        $postId = intval(input("post.postId"));
        $nickname = strval(input("post.nickname"));
        $postComment = strval(input("post.postComment"));

        $nickname = htmlspecialchars($nickname, ENT_NOQUOTES);
        $postComment = htmlspecialchars($postComment, ENT_NOQUOTES);

        $findMaxFloorCmd = [
            'find' => 'post',
            'filter' => [
                'postId' => $postId,
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1,
                'postComment' => [
                    '$slice' => 1
                ]
            ],
            'limit' => 1,
        ];
        $cmdArr = Mongo::cmd($findMaxFloorCmd);
        if (empty($cmdArr)) {
            return json(['code' => 200, 'msg' => 'ok']);
        }
        if (property_exists($cmdArr[0],'postComment')) {
            $floor = $cmdArr[0]->postComment[0]->floor + 1;
        } else {
            $floor = 1;
        }

        $commentTime = new UTCDateTime();

        $document = [
            'commentId' => new ObjectId(),
            'content' => $postComment,
            'nickname' => $nickname,
            'commentTime' => $commentTime,
            'floor' => $floor,
            'status' => 'ONLINE',
            'ip' => $this->ip,
            'userAgent' => $this->userAgent,
        ];

        $address = (new IpUtil())->getAddressByIp($this->ip);
        if ($address != null) {
            $document['address'] = $address;
        }

        if (ini_get("browscap")) {
            $userAgentParseResult = get_browser($this->userAgent, true);
            $document['browser'] = $userAgentParseResult['parent'];
            $document['os'] = $userAgentParseResult['platform'];
        }

        $postCommentCmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        'postId' => $postId
                    ],
                    'u' => [
                        '$push' => [
                            'postComment' => [
                                '$each'=>[
                                    $document
                                ],
                                '$sort'=>[
                                    'commentTime' => -1
                                ]
                            ]
                        ],
                        '$inc' => [
                            'commentCount' => 1
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]

        ];
        Mongo::cmd($postCommentCmd);
        return json(['code' => 200, 'msg' => 'ok']);
    }

}