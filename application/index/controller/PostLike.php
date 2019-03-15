<?php

namespace app\index\controller;


use app\common\util\Mongo;

class PostLike extends Base {
    public function likePost() {
        $postId = intval(input("post.postId"));
        $postLikeExistCmd = [
            'find' => 'post',
            'filter' => [
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
                            'postLike' => [
                                'ip' => $this->ip
                            ]
                        ]
                    ]
                ]
            ]
        ];
        Mongo::cmd($postLikeCmd);
        return json(['code' => 200]);
    }
}