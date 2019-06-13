<?php

namespace app\index\controller;


use app\common\util\IpUtil;
use app\common\util\Mongo;
use MongoDB\BSON\UTCDateTime;

class PostLike extends Base {
    public function likePost() {
        $postId = intval(input("post.postId"));
        $postLikeExistCmd = [
            'find' => 'post',
            'filter' => [
                'postId' => intval($postId),
                'postLike' => [
                    '$elemMatch' => [
                        'ip' => $this->ip
                    ]
                ]
            ],
            'projection' => [
                '_id' => 0,
                'postId' => 1
            ]
        ];
        $existResult = Mongo::cmd($postLikeExistCmd);
        if (!empty($existResult)) {
            return json(['code' => -1]);
        }
        $postLikeDocument = [
            'ip' => $this->ip,
            'likeTime' => new UTCDateTime()
        ];
        $address = (new IpUtil())->getAddressByIp($this->ip);
        if ($address != null) {
            $postLikeDocument['address'] = $address;
        }
        $postLikeCmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        'postId' => $postId
                    ],
                    'u' => [
                        '$inc' => [
                            'likeCount' => 1
                        ],
                        '$addToSet' => [
                            'postLike' => $postLikeDocument
                        ]
                    ]
                ]
            ]
        ];
        Mongo::cmd($postLikeCmd);
        return json(['code' => 200]);
    }
}