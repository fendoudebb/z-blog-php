<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Log;

class TopicAdd extends BaseRoleAdmin {

    public function addTopic() {
        $topicName = input('post.topicName');
        $topicParentId = input('post.topicParentId');
        if (!isset($topicName)) {
            Log::log("add topic, missing params: topic name. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_NAME);
        }
        if (!isset($topicParentId)) {
            Log::log("add topic, missing params: topic parent id. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
        }
        if (!is_numeric($topicParentId)) {
            Log::log("add topic, illegal argument: topic parent id. operator[$this->username]");
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
        }
        $isExist = Db::table('topic')
            ->where('name', $topicName)
            ->value('id');
        if ($isExist) {
            Log::log("add topic, topic name[$topicName] exists already. operator[$this->username]");
            return $this->fail(ResCode::TOPIC_NAME_EXISTS);
        }
        Db::startTrans();
        $sort = Db::table('topic')
            ->where('parent_id', $topicParentId)
            ->max('sort');
        $insertResult = Db::table('topic')
            ->insert([
                'name' => $topicName,
                'parent_id' => $topicParentId,
                'sort' => $sort + 1
            ]);
        if (!$insertResult) {
            Db::rollback();
            Log::log("add topic, insert [$topicName] into table topic fail. operator[$this->username]");
            return $this->fail(ResCode::TABLE_INSERT_FAIL);
        }
        Db::commit();
        return $this->res();
    }

}