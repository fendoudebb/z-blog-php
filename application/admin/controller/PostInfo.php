<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class PostInfo extends BaseRoleAdmin {

    public function postInfo() {
        $postId = input('post.postId');
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (!is_numeric($postId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        try {
            $post = Db::table('post p')
                ->field('p.id as postId, p.title, p.is_private as isPrivate, c.content')
                ->join('post_content c', 'p.id = c.post_id')
                ->where('p.id', $postId)
                ->find();
            return $this->res($post);
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return $this->exception();
        }


    }

}