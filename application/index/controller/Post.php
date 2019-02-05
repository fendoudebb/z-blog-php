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
            $postKey = RedisKey::HASH_POST_DETAIL . $postId;
            $pipeline = Redis::init()->multi(\Redis::PIPELINE);
            $pipeline->sIsMember(RedisKey::SET_NONEXISTENT_POST, $postId);
            $pipeline->hMGet($postKey, [
                RedisKey::POST_TITLE,
                RedisKey::POST_KEYWORDS,
                RedisKey::POST_DESC,
                RedisKey::POST_STATUS,
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
            if ($result[0] === true) {
                $this->log('post不存在 防缓存击穿');
                return redirect('/404.html');
            }
            if ($result[1][RedisKey::POST_TITLE] !== false) {
                if ($result[1][RedisKey::POST_STATUS] != 1 || $result[1][RedisKey::POST_IS_PRIVATE] == 1) {
                    Redis::init()->sAdd(RedisKey::SET_NONEXISTENT_POST, $postId);
                    $this->log("post status isn't online, or is private");
                    return redirect('/404.html');
                }
                $this->log("post[$postId], redis缓存");
                return compressHtml($this->fetch('post', $result[1]));
            }
            $post = Db::table('post p')
                ->field("DATE_FORMAT(p.post_time, '%Y-%m-%d') AS postTime, p.title, p.keywords, p.description, 
                p.status, p.is_private as isPrivate, p.is_comment_close as isCommentClose, 
                p.is_copy as isCopy, p.original_link as originalLink, 
                p.pv, p.comment_count as commentCount, p.like_count as likeCount, 
                c.content, c.markup_language")
                ->join('post_content c', 'p.id = c.post_id')
                ->where('p.id', $postId)
                ->find();
            if (!isset($post)) {
                Redis::init()->sAdd(RedisKey::SET_NONEXISTENT_POST, $postId);
                $this->log('post不存在，存入不存在列表');
                return redirect('/404.html');
            }
            $parser = new Parser;
            $html = $parser->makeHtml($post['content']);
            $postStatus = $post['status'];
            $postIsPrivate = $post['isPrivate'];
            $p = [
                RedisKey::POST_TITLE => $post['title'],
                RedisKey::POST_KEYWORDS => $post['keywords'],
                RedisKey::POST_DESC => $post['description'],
                RedisKey::POST_STATUS => $postStatus,
                RedisKey::POST_TIME => $post['postTime'],
                RedisKey::POST_IS_PRIVATE => $postIsPrivate,
                RedisKey::POST_IS_COMMENT_CLOSE => $post['isCommentClose'],
                RedisKey::POST_IS_COPY => $post['isCopy'],
                RedisKey::POST_ORIGINAL_LINK => $post['originalLink'],
                RedisKey::POST_PV => $post['pv'],
                RedisKey::POST_COMMENT_COUNT => $post['commentCount'],
                RedisKey::POST_LIKE_COUNT => $post['likeCount'],
                RedisKey::POST_HTML => $html,
            ];
            $compressHtml = compressHtml($this->fetch('post', $p));
            Redis::init()->hMSet($postKey, $p);
            if ($postStatus != 1 || $postIsPrivate == 1) {
                Redis::init()->sAdd(RedisKey::SET_NONEXISTENT_POST, $postId);
                $this->log("post status[$postStatus], is private[$postIsPrivate]");
                return redirect('/404.html');
            }
            return $compressHtml;
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return redirect('/404.html');
        }
    }

}