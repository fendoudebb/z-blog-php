<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use app\common\util\Parsedown;
use app\common\util\Parser;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

class PostPublish extends BaseRoleAdmin {

    public function publishPost() {
        $postId = input('post.id');
        $postTitle = input('post.title');
        $postContent = input('post.content');
        $postTopics = input('post.topics/a');
        $postProp = strval(input('post.postProp'));
        $isPrivate = boolval(input('post.isPrivate'));
        $parser = new Parsedown();
        $html = $parser->text($postContent);
        /*$parser = new Parser();
        $html = $parser->makeHtml($postContent);*/
        if (isset($postId)) {//修改
            $stripTagsHtml = strip_tags($html);
            $postWordCount = mb_strlen($stripTagsHtml, 'utf-8');
            $description = mb_substr($stripTagsHtml, 0, 80, 'utf-8');
            $updatePostCmd = [
                'update' => 'post',
                'updates' => [
                    [
                        'q' => [
                            '_id' => new ObjectId($postId)
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
        return $this->res();
    }

}