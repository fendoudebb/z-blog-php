<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class Topic extends BaseRoleAdmin {

    public function topicList() {
        $topicParentId = input('post.topicParentId');
        $page = input('post.page');
        $size = input('post.size');
        if (!isset($topicParentId)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
        }
        if (!is_numeric($topicParentId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
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
                ->order('sort')
                ->page($page, $size)
                ->select();
            return $this->res($topic);
        } catch (Exception $e) {
            $this->log($e->getMessage(), true);
            return $this->exception();
        }
    }

}