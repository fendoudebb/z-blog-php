<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\ElasticsearchUtil;
use app\common\util\Mongo;
use app\common\util\MyParsedown;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class PostPublish extends BaseRoleNormal {

    public function publishPost() {
        $postId = intval(input('post.id'));
        $postTitle = input('post.title');
        $postContent = input('post.content');
        $postTopics = input('post.topics/a');
        $postProp = strval(input('post.postProp'));
        $isPrivate = boolval(input('post.isPrivate'));
//        $parser = new Parsedown();
//        $html = $parser->setSafeMode(true)->text($postContent);
//        $html = $parser->text($postContent);
        /*$parser = new Parser();
        $html = $parser->makeHtml($postContent);*/
        $parser = new MyParsedown();
        $html = $parser->text($postContent);

        if (isset($postId) && $postId > 0) {//修改
            $stripTagsHtml = strip_tags($html);
            $postWordCount = mb_strlen($stripTagsHtml, 'utf-8');
            $description = mb_substr($stripTagsHtml, 0, 80, 'utf-8');
            $updatePostCmd = [
                'update' => 'post',
                'updates' => [
                    [
                        'q' => [
                            'postId' => $postId,
                            'userId' => new ObjectId($this->userId)
                        ],
                        'u' => [
                            '$set' => [
                                'title' => $postTitle,
                                'description' => $description,
                                'content' => $postContent,
                                'contentHtml' => $html,
                                'topics' => $postTopics,
                                'postProp' => $postProp,
                                'postStatus' => $isPrivate ? 'PRIVATE' : 'AUDIT',
                                'postWordCount' => $postWordCount
                            ],
                            '$currentDate' => [
                                'lastModified' => true
                            ],
                        ]
                    ]
                ]
            ];
            $updateResult = Mongo::cmd($updatePostCmd);
            if (!$updateResult[0]->ok) {
                return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
            }
        } else {//新增
            $findMaxPostIdCmd = [
                'find' => 'post',
                'sort' => [
                    'postId' => -1
                ],
                'projection' => [
                    '_id' => 0,
                    'postId' => 1
                ],
                'limit' => 1,
            ];
            $cmdArr = Mongo::cmd($findMaxPostIdCmd);
            if (empty($cmdArr)) {
                $postId = 1;
            } else {
                if (property_exists($cmdArr[0],'postId')) {
                    $postId = $cmdArr[0]->postId + 1;
                } else {
                    $postId = 1;
                }
            }
            $postTime = new UTCDateTime();
            $stripTagsHtml = strip_tags($html);
            $postWordCount = mb_strlen($stripTagsHtml, 'utf-8');
            $description = mb_substr($stripTagsHtml, 0, 80, 'utf-8');
            $document = [
                'userId' => new ObjectId($this->userId),
                'postId' => $postId,
                'postTime' => $postTime,
                'title' => $postTitle,
                'description' => $description,
                'topics' => $postTopics,
                'content' => $postContent,
                'contentHtml' => $html,
                'postProp' => $postProp,
                'postWordCount' => $postWordCount,
                'commentStatus' => 'OPEN',
                'postStatus' => $isPrivate ? 'PRIVATE' : 'AUDIT',
                'pv' => 0,
                'likeCount' => 0,
                'commentCount' => 0,
            ];
            $insertPostCmd = [
                'insert' => 'post',
                'documents' => [
                    $document
                ]
            ];
            $insertPostResult = Mongo::cmd($insertPostCmd);
            if (empty($insertPostResult) || !$insertPostResult[0]->ok) {
                $this->log(ResCode::COLLECTION_INSERT_FAIL);
                return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
            }
        }
        $content = strip_tags($html);
        $content = str_replace("\t", "", $content);//tab
        $content = str_replace("\r\n", "", $content);//回车
        $content = str_replace("\r", "", $content);//换行
        $content = str_replace("\n", "", $content);//换行
        $content = trim($content, " ");
        $param = [
            "postId" => $postId,
            "postTime" => now(),
            "offline" => true,
            "topics" => $postTopics,
            "title" => $postTitle,
            "content" => $content,
        ];
        ElasticsearchUtil::PUT("http://localhost:9200/post/_doc/" . $postId, $param);
        return $this->res();
    }

}