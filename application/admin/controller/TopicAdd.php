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
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::MISSING_PARAMS_TOPIC_NAME);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_NAME);
        }
        if (!isset($topicParentId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
        }
        if (!is_numeric($topicParentId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
        }
        $isExist = Db::table('topic')
            ->where('name', $topicName)
            ->value('id');
        if ($isExist) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::TOPIC_NAME_ALREADY_EXISTS);
            return $this->fail(ResCode::TOPIC_NAME_ALREADY_EXISTS);
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
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::TABLE_INSERT_FAIL);
            return $this->fail(ResCode::TABLE_INSERT_FAIL);
        }
        Db::commit();
        return $this->res();
    }

}