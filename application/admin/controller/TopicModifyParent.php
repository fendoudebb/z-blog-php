<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class TopicModifyParent extends BaseRoleAdmin {

    public function modifyTopicParent() {
        $topicId = input('post.topicId');
        $topicParentId = input('post.topicParentId');
        if (!isset($topicId)) {
            Log::log("modify topic parent id, missing params: topic id. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_ID);
        }
        if (!isset($topicParentId)) {
            Log::log("modify topic parent id, missing params: topic parent id. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
        }
        if (!is_numeric($topicId)) {
            Log::log("modify topic parent id, illegal argument: topic id. operator[$this->username]");
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        if (!is_numeric($topicParentId)) {
            Log::log("modify topic parent id, illegal argument: topic parent id. operator[$this->username]");
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
        }
        try {
            Db::startTrans();
            $existId = Db::table('topic')
                ->where('parent_id', $topicParentId)
                ->value('id');
            if (!isset($existId)) {
                Log::log("modify topic parent id, topic parent id not exists. operator[$this->username]");
                return $this->fail(ResCode::TOPIC_PARENT_ID_NOT_EXISTS);
            }
            $updateParentIdResult = Db::table('topic')
                ->where('id', $topicId)
                ->update([
                    'parent_id' => $topicParentId
                ]);
            if (!$updateParentIdResult) {
                Db::rollback();
                Log::log("modify topic parent id, update table fail. operator[$this->username]");
                return $this->fail(ResCode::TABLE_UPDATE_FAIL);
            }
            Db::commit();
            return $this->res();
        } catch (Exception $e) {
            Db::rollback();
            Log::log("modify topic parent id, operator[$this->username], exception->" . $e->getMessage());
            return $this->exception();
        }
    }
}