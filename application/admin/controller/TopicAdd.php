<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Log;

class TopicAdd extends BaseRoleAdmin {

    public function addTopic() {
        $topicName = input('post.topicName');
        $topicType = input('post.topicType');
        if (!isset($topicName)) {
            Log::log("add topic, missing params: topic name. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_NAME);
        }
        if (!isset($topicType)) {
            Log::log("add topic, missing params: topic type. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_TYPE);
        }
        if (!is_numeric($topicType) || ($topicType != 0 && $topicType != 1)) {
            Log::log("add topic, illegal argument: topic type. operator[$this->username]");
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_TYPE);
        }
        $isExist = Db::table('topic')
            ->where('name', $topicName)
            ->value('id');
        if ($isExist) {
            Log::log("add topic, topic name[$topicName] exists already. operator[$this->username]");
            return $this->fail(ResCode::TOPIC_NAME_EXISTS);
        }
        $insertResult = Db::table('topic')
            ->insert([
                'name' => $topicName,
                'is_parent' => $topicType
            ]);
        if (!$insertResult) {
            Log::log("add topic, insert [$topicName] into table topic fail. operator[$this->username]");
            return $this->fail(ResCode::TABLE_INSERT_FAIL);
        }
        return $this->res();
    }

}