<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Parser;
use think\Db;

class PostPublish extends BaseRoleAdmin {

    public function publishPost() {
        $postTitle = input('post.title');
        $postContent = input('post.content');
        $postTopic = input('post.topic/a');
        $isCopy = boolval(input('post.isCopy'));
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
        $cmdArr = Db::cmd($findMaxPostIdCmd);
        if (empty($cmdArr)) {
            $postId = 1;
        } else {
            if (array_key_exists('postId', $cmdArr[0])) {
                $postId = $cmdArr[0]['postId'] + 1;
            } else {
                $postId = 1;
            }

        }
        $postTime = new \MongoDB\BSON\UTCDateTime();
        $parser = new Parser;
        $html = $parser->makeHtml($postContent);
        $document = [
            'userId' => new \MongoDB\BSON\ObjectId($this->userId),
            'postId' => $postId,
            'postTime' => $postTime,
            'title' => $postTitle,
            'keywords' => $postTitle,
            'description' => $postTitle,
            'content' => $postContent,
            'content_html' => $html,
            'isCopy' => $isCopy,
            'isPrivate' => $isPrivate,
            'isCommentClose' => false,
            'status' => 0,
            'pv' => 0,
            'likeCount' => 0,
            'commentCount' => 0,
        ];
        if (!empty($postTopic)) {
            $document['topic'] = $postTopic;
        }
        $insertPostCmd = [
            'insert' => 'post',
            'documents' => [
                $document
            ]
        ];
        $insertPostResult = Db::cmd($insertPostCmd);
        if (empty($insertPostResult) || !$insertPostResult[0]['ok']) {
            $this->log(ResCode::COLLECTION_INSERT_FAIL);
            return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
        }
        return $this->res();
    }

}