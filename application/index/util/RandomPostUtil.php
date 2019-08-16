<?php


namespace app\index\util;


use app\common\util\Mongo;
use stdClass;

class RandomPostUtil {

    public function getPostRandom($postId) {
        $postRandomCmd = [
            'aggregate' => 'post',
            'pipeline' => [
                [
                    '$match' => [
                        'postId' => [
                            '$ne' => $postId
                        ],
                        'postStatus' => 'ONLINE'
                    ]
                ],
                [
                    '$project' => [
                        '_id' => 0,
                        'postId' => 1,
                        'title' => 1,
                        'pv' => 1
                    ]
                ],
                [
                    '$sample' => [
                        'size' => 10
                    ]
                ]
            ],
            'cursor' => new stdClass()
        ];
        $post = Mongo::cmd($postRandomCmd);
        return $post;
    }


}