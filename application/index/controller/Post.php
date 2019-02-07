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
            $pipeline->sIsMember(RedisKey::SET_VISIBLE_POST, $postId);
            $pipeline->hMGet($postKey, [
                RedisKey::POST_TITLE,
                RedisKey::POST_KEYWORDS,
                RedisKey::POST_DESC,
                RedisKey::POST_TIME,
                RedisKey::POST_IS_COMMENT_CLOSE,
                RedisKey::POST_IS_COPY,
                RedisKey::POST_ORIGINAL_LINK,
                RedisKey::POST_PV,
                RedisKey::POST_COMMENT_COUNT,
                RedisKey::POST_LIKE_COUNT,
                RedisKey::POST_HTML,
            ]);
            $pipeline->sort(RedisKey::SET_VISIBLE_POST, ['by'=>RedisKey::HASH_POST_DETAIL.'*->pv','limit'=>[0,5],'get'=>['#',RedisKey::HASH_POST_DETAIL.'*->title',RedisKey::HASH_POST_DETAIL.'*->pv'],'sort'=>'desc']);
            $pipeline->sort(RedisKey::SET_VISIBLE_POST, ['by'=>RedisKey::HASH_POST_DETAIL.'*->commentCount','limit'=>[0,5],'get'=>['#',RedisKey::HASH_POST_DETAIL.'*->title',RedisKey::HASH_POST_DETAIL.'*->commentCount'],'sort'=>'desc']);
            $pipeline->sort(RedisKey::SET_VISIBLE_POST, ['by'=>RedisKey::HASH_POST_DETAIL.'*->likeCount','limit'=>[0,5],'get'=>['#',RedisKey::HASH_POST_DETAIL.'*->title',RedisKey::HASH_POST_DETAIL.'*->likeCount'],'sort'=>'desc']);
            $result = $pipeline->exec();
            if ($result[0] === false) {
                $this->log('post不存在 防缓存击穿');
                return redirect('/404.html');
            }
            $result[1]['pvRank'] = array_chunk($result[2],3);
            $result[1]['commentRank'] = array_chunk($result[3],3);
            $result[1]['likeRank'] = array_chunk($result[4],3);
            if ($result[1][RedisKey::POST_TITLE] !== false) {
                $this->log("post[$postId], redis缓存");
                return compressHtml($this->fetch('post', $result[1]));
            }
            $post = Db::table('post p')
                ->field("p.post_time AS postTime, p.title, p.keywords, p.description, 
                p.status, p.is_private as isPrivate, p.is_comment_close as isCommentClose, 
                p.is_copy as isCopy, p.original_link as originalLink, 
                p.pv, p.comment_count as commentCount, p.like_count as likeCount, 
                c.content, c.markup_language")
                ->join('post_content c', 'p.id = c.post_id')
                ->where('p.id', $postId)
                ->find();
            if (!isset($post)) {
                $this->log('post不存在');
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
            Redis::init()->hMSet($postKey, $p);
            if ($postStatus != 1 || $postIsPrivate == 1) {
                Redis::init()->sRem(RedisKey::SET_VISIBLE_POST, $postId);
                $this->log("post status[$postStatus], is private[$postIsPrivate]");
                return redirect('/404.html');
            }
            $compressHtml = compressHtml($this->fetch('post', $p));
            return $compressHtml;
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return redirect('/404.html');
        }
    }

}