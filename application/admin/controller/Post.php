<?php

namespace app\admin\controller;


use think\Db;

class Post extends BaseRoleAdmin {

    public function postList() {
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
            'aggregate' => 'post', // collection表名
            'pipeline' => [
                [
                    '$lookup' => [
                        'from' => 'sys_user',
                        'localField' => 'userId',
                        'foreignField' => '_id',
                        'as' => 'sysUser'
                    ]
                ],
                [
                    '$unwind' => '$sysUser'
                ],
                [
                    '$project' => [
                        'sysUser.username' => 1,
                        'postId' => 1,
                        'postTime' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:%M:%S",
                                'date' => [
                                    '$toDate' => '$postTime'
                                ],
                                'timezone' => "+08:00"
                            ]
                        ],
                        'postStatus' => 1,
                        'title' => 1,
                        'keywords' => 1,
                        'description' => 1,
                        'topics' => 1,
                        'commentStatus' => 1,
                        'postProp' => 1,
                        'isTop' => 1,
                        'pv' => 1,
                        'commentCount' => 1,
                        'likeCount' => 1,
                        'createTime' => [
                            '$dateToString' => [
                                'format' => "%Y-%m-%d %H:%M:%S",
                                'date' => [
                                    '$toDate' => '$_id'
                                ],
                                'timezone' => "+08:00"

                            ]
                        ],
                    ]
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
        $post = Db::cmd($cmd);
        $response = [
        ];
        $cmd = [
            'count' => 'post'
        ];
        $countResult = Db::cmd($cmd);
        $response['totalCount'] = $countResult[0]['n'];
        $response['post'] = $post;
        return $this->res($response);
    }
}