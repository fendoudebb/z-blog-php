<?php

namespace app\admin\controller;


use app\common\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Parser;
use app\common\util\Redis;
use think\Db;

class PostPublish extends BaseRoleAdmin {

    public function publishPost() {
        $postTitle = input('post.title');
        $postContent = input('post.content');
        $postTopic = input('post.topic');
        $isCopy = input('post.isCopy');
        $isPrivate = input('post.isPrivate');

        Db::startTrans();
        $insertPostId = Db::table('post')
            ->insertGetId([
                'user_id' => $this->userId,
                'title' => $postTitle,
                'is_private' => $isPrivate
            ]);
        if (!$insertPostId) {
            Db::rollback();
            $this->log(ResCode::TABLE_INSERT_FAIL);
            return $this->fail(ResCode::TABLE_INSERT_FAIL);
        }
        $insertPostContentResult = Db::table('post_content')
            ->insert([
                'post_id' => $insertPostId,
                'content' => $postContent
            ]);
        if (!$insertPostContentResult) {
            Db::rollback();
            $this->log(ResCode::TABLE_INSERT_FAIL);
            return $this->fail(ResCode::TABLE_INSERT_FAIL);
        }
        $parser = new Parser;
        $html = $parser->makeHtml($postContent);
        $p = [
            RedisKey::POST_TITLE => $postTitle,
            RedisKey::POST_KEYWORDS => $post['keywords'],
            RedisKey::POST_DESC => $post['description'],
            RedisKey::POST_STATUS => 0,
            RedisKey::POST_TIME => $post['postTime'],
            RedisKey::POST_IS_PRIVATE => $isPrivate,
            RedisKey::POST_IS_COMMENT_CLOSE => 0,
            RedisKey::POST_IS_COPY => 0,
            RedisKey::POST_ORIGINAL_LINK => '',
            RedisKey::POST_PV => 0,
            RedisKey::POST_COMMENT_COUNT => 0,
            RedisKey::POST_LIKE_COUNT => 0,
            RedisKey::POST_HTML => $html,
        ];
        Redis::init()->hMSet($postKey, $p);
        Db::commit();
        return $this->res();
    }

}