<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class Topic extends BaseRoleAdmin {

    public function topicInfo() {
        $topicType = input('post.topicType');
        $page = input('post.page');
        $size = input('post.size');
        if (!isset($topicType)) {
            Log::log("topic info, missing params: topic type. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_TYPE);
        }
        if (!is_numeric($topicType) || ($topicType != 0 && $topicType != 1)) {
            Log::log("topic info, illegal argument: topic type. operator[$this->username]");
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_TYPE);
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
                ->where('is_parent', $topicType)
                ->page($page, $size)
                ->select();
            return $this->res($topic);
        } catch (Exception $e) {
            Log::log("topic info, exception->" . $e->getMessage());
            return $this->exception();
        }
    }

}