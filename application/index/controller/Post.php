<?php

namespace app\index\controller;


use app\common\util\Parser;
use think\Db;
use think\Exception;

class Post extends Base {

    public function post($postId) {
        try {
            $post = Db::table('post p')
                ->field("DATE_FORMAT(p.post_time, '%Y-%m-%d') AS postTime, p.title, p.keywords, p.description, p.is_copy as isCopy, p.pv, p.like_count as likeCount, c.content, c.markup_language")
                ->join('post_content c', 'p.id = c.post_id')
                ->where('p.id', $postId)
                ->find();
            if (!isset($post)) {
                $this->log('post不存在');
                return compressHtml($this->fetch('public/404'));
            }
            $parser = new Parser;
            $html = $parser->makeHtml($post['content']);
            $post['content'] = $html;
            return compressHtml($this->fetch('post', $post));
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return compressHtml($this->fetch('public/404'));
        }
    }

}