<?php

namespace app\index\controller;


use app\common\util\ElasticsearchUtil;
use app\common\util\Mongo;

class EsImportMongoData extends Base{

    public function import() {
        $page = 0;
        $size = 1000;
        if ($page < 1) {
            $page = 1;
        }
        /*if ($size < 1 || $size > 20) {
            $size = 20;
        }*/
        $offset = ($page - 1) * $size;
        $cmd = [
            'aggregate' => 'post', // collection表名
            'pipeline' => [
                /*[
                    '$lookup' => [
                        'from' => 'sys_user',
                        'localField' => 'userId',
                        'foreignField' => '_id',
                        'as' => 'sysUser'
                    ]
                ],
                [
                    '$unwind' => '$sysUser'
                ],*/
                [
                    '$project' => [
//                        'sysUser.username' => 1,
                        '_id' => 0,
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
                        'contentHtml' => 1,
                        'topics' => 1,
                    ],
                ],
                [
                    '$sort' => ['postId' => -1]
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
        $post = Mongo::cmd($cmd);
        foreach ($post as $p) {
            $postId = $p->postId;
            $postTime = $p->postTime;
            $contentHtml = $p->contentHtml;
            $content = strip_tags($contentHtml);
            $content = str_replace("\t", "", $content);//tab
            $content = str_replace("\r\n", "", $content);//回车
            $content = str_replace("\r", "", $content);//换行
            $content = str_replace("\n", "", $content);//换行
            $content = trim($content, " ");
            $param = [
                "postId" => $postId,
                "postTime" => $postTime,
                "offline" => ($p->postStatus === 'OFFLINE'),
                "topics" => $p->topics,
                "title" => $p->title,
                "content" => $content,
            ];
            ElasticsearchUtil::PUT("http://localhost:9200/post/_doc/" . $postId, $param);
        }
    }
}