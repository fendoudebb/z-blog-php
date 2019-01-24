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
        if (!isset($page) || !is_numeric($page) || $page < 1) {
            $page = 1;
        }
        if (!isset($size) || !is_numeric($size) || $size < 1 || $size > 20) {
            $size = 20;
        }
        try {
            $response = [
                'currentPage' => $page,
                'pageSize' => $size,
            ];
            $count = Db::table('topic')
                ->where('parent_id', $topicParentId)
                ->count();
            $offset = ($page - 1) * $size;
            $topic = Db::table('topic')
                ->field('id, name')
                ->where('parent_id', $topicParentId)
                ->order('sort')
                ->limit($offset, $size)
                ->select();
            $response['totalCount'] = $count;
            $response['totalPage'] = ceil($count / $size);
            $response['topic'] = $topic;
            return $this->res($response);
        } catch (Exception $e) {
            $this->log($e->getMessage(), true);
            return $this->exception();
        }
    }

}