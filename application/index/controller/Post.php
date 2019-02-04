<?php

namespace app\index\controller;


use app\common\config\RedisKey;
use app\common\util\Parser;
use app\common\util\Redis;
use think\Db;
use think\Exception;

class Post extends Base {

    public function post($postId) {
        try {
            $postKey = RedisKey::HASH_POST . $postId;
            $pipeline = Redis::init()->multi(\Redis::PIPELINE);
            $pipeline->sIsMember(RedisKey::SET_NONEXISTENT_POST, $postId);
            $pipeline->exists($postKey);
            $pipeline->hMGet($postKey, [
                RedisKey::POST_TITLE,
                RedisKey::POST_KEYWORDS,
                RedisKey::POST_DESC,
                RedisKey::POST_TIME,
                RedisKey::POST_IS_PRIVATE,
                RedisKey::POST_IS_COMMENT_CLOSE,
                RedisKey::POST_IS_COPY,
                RedisKey::POST_ORIGINAL_LINK,
                RedisKey::POST_PV,
                RedisKey::POST_COMMENT_COUNT,
                RedisKey::POST_LIKE_COUNT,
                RedisKey::POST_HTML,
            ]);
            $result = $pipeline->exec();
            var_dump($result);
            if ($result[0] === true) {
                $this->log('post缓存不存在');
                return redirect('/404.html');
            }
            if ($result[1] !== false) {
                return compressHtml($this->fetch('post', $result[2]));
            }
            $post = Db::table('post p')
                ->field("DATE_FORMAT(p.post_time, '%Y-%m-%d') AS postTime, p.title, p.keywords, p.description, p.is_copy as isCopy, p.original_link as originalLink, p.pv, p.comment_count as commentCount, p.like_count as likeCount, c.content, c.markup_language")
                ->join('post_content c', 'p.id = c.post_id')
                ->where('p.id', $postId)
                ->where('p.status', 1)
                ->find();
            if (!isset($post)) {
                $pipeline = Redis::init()->multi(\Redis::PIPELINE);
                $pipeline->del($postKey);
                $pipeline->sAdd(RedisKey::SET_NONEXISTENT_POST, $postId);
                $pipeline->exec();
                $this->log('post不存在，存入不存在列表');
                return redirect('/404.html');
            }
            $parser = new Parser;
            $html = $parser->makeHtml($post['content']);
            $p = [
                RedisKey::POST_TITLE => $post['title'],
                RedisKey::POST_KEYWORDS => $post['keywords'],
                RedisKey::POST_DESC => $post['description'],
                RedisKey::POST_TIME => $post['postTime'],
                RedisKey::POST_IS_PRIVATE => $post['isPrivate'],
                RedisKey::POST_IS_COMMENT_CLOSE => $post['isCommentClose'],
                RedisKey::POST_IS_COPY => $post['isCopy'],
                RedisKey::POST_ORIGINAL_LINK => $post['originalLink'],
                RedisKey::POST_PV => $post['pv'],
                RedisKey::POST_COMMENT_COUNT => $post['commentCount'],
                RedisKey::POST_LIKE_COUNT => $post['likeCount'],
                RedisKey::POST_HTML => $html,
            ];
            Redis::init()->hMSet($postKey, $p);
            return compressHtml($this->fetch('post', $p));
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return redirect('/404.html');
        }
    }

}