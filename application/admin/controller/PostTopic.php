<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class PostTopic extends BaseRoleAdmin {

    public function postTopic() {
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
            $postTopic = Db::table('post_topic pt')
                ->field('t.id as topicId, t.name as topicName, pt.is_delete as isDelete')
                ->join('post p', 'pt.post_id = p.id')
                ->join('topic t', 'pt.topic_id = t.id')
                ->where('post_id', $postId)
                ->select();
            return $this->res($postTopic);
        } catch (Exception $e) {
            $this->log($e->getMessage(), true);
            return $this->exception();
        }
    }
}