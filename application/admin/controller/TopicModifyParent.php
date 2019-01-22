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
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::MISSING_PARAMS_TOPIC_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_ID);
        }
        if (!isset($topicParentId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
        }
        if (!is_numeric($topicId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        if (!is_numeric($topicParentId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
        }
        try {
            Db::startTrans();
            $existId = Db::table('topic')
                ->where('parent_id', $topicParentId)
                ->value('id');
            if (!isset($existId)) {
                Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::TOPIC_PARENT_ID_DOES_NOT_EXIST);
                return $this->fail(ResCode::TOPIC_PARENT_ID_DOES_NOT_EXIST);
            }
            $updateParentIdResult = Db::table('topic')
                ->where('id', $topicId)
                ->update([
                    'parent_id' => $topicParentId
                ]);
            if (!$updateParentIdResult) {
                Db::rollback();
                Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::TABLE_UPDATE_FAIL);
                return $this->fail(ResCode::TABLE_UPDATE_FAIL);
            }
            Db::commit();
            return $this->res();
        } catch (Exception $e) {
            Db::rollback();
            Log::log(__FUNCTION__ . "-operator[$this->username]: exception-> " . $e->getMessage());
            return $this->exception();
        }
    }
}