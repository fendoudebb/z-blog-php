<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use app\common\util\Parser;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\Db;

class PostPublish extends BaseRoleAdmin {

    public function publishPost() {
        $postTitle = input('post.title');
        $postContent = input('post.content');
        $postTopics = input('post.topics/a');
        $postProp = strval(input('post.postProp'));
        $isPrivate = boolval(input('post.isPrivate'));
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
            if (property_exists('postId', $cmdArr[0])) {
                $postId = $cmdArr[0]->postId + 1;
            } else {
                $postId = 1;
            }
        }
        $postTime = new UTCDateTime();
        $parser = new Parser;
        $html = $parser->makeHtml($postContent);
        $description = mb_substr(strip_tags($html), 0, 80, 'utf-8');
        $document = [
            'userId' => new ObjectId($this->userId),
            'postId' => $postId,
            'postTime' => $postTime,
            'title' => $postTitle,
            'keywords' => $postTitle,
            'description' => $description,
            'content' => $postContent,
            'contentHtml' => $html,
            'postProp' => $postProp,
            'commentStatus' => 'OPEN',
            'postStatus' => $isPrivate ? 'PRIVATE' : 'AUDIT',
            'pv' => 0,
            'likeCount' => 0,
            'commentCount' => 0,
        ];
        if (!empty($postTopics)) {
            $document['topics'] = $postTopics;
        }
        $insertPostCmd = [
            'insert' => 'post',
            'documents' => [
                $document
            ]
        ];
        $insertPostResult = Db::cmd($insertPostCmd);
        if (empty($insertPostResult) || !$insertPostResult[0]->ok) {
            $this->log(ResCode::COLLECTION_INSERT_FAIL);
            return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
        }
        return $this->res();
    }

}