<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class Topic extends BaseRoleAdmin {

    public function topicList() {
        $topicParentId = input('post.topicParent');
        $page = input('post.page');
        $size = input('post.size');
        if (!isset($topicParentId)) {
            Log::log("topic list, missing params: topic parent id. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
        }
        if (!is_numeric($topicParentId)) {
            Log::log("topic list, illegal argument: topic parent id. operator[$this->username]");
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
        }
        if (!isset($page)) {
            $page = 1;
        }
        if (!isset($size) || $size >= 20) {
            $size = 20;
        }
        try {
            $topic = Db::table('topic')
                ->field('id, name')
                ->where('parent_id', $topicParentId)
                ->page($page, $size)
                ->select();
            return $this->res($topic);
        } catch (Exception $e) {
            Log::log("topic list, operator[$this->username]. exception->" . $e->getMessage());
            return $this->exception();
        }
    }

}