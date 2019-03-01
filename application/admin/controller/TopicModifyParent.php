<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class TopicModifyParent extends BaseRoleAdmin {

    public function modifyTopicParent() {
        $topicId = input('post.topicId');
        $topicParentId = input('post.topicParentId');
        if (!isset($topicId)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_ID);
        }
        if (!isset($topicParentId)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_PARENT_ID);
        }
        if (!is_numeric($topicId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        if (!is_numeric($topicParentId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_PARENT_ID);
        }
        try {
            Db::startTrans();
            $existId = Db::table('topic')
                ->where('parent_id', $topicParentId)
                ->value('id');
            if (!isset($existId)) {
                $this->log(ResCode::TOPIC_PARENT_ID_DOES_NOT_EXIST);
                return $this->fail(ResCode::TOPIC_PARENT_ID_DOES_NOT_EXIST);
            }
            $updateParentIdResult = Db::table('topic')
                ->where('id', $topicId)
                ->update([
                    'parent_id' => $topicParentId
                ]);
            if (!$updateParentIdResult) {
                Db::rollback();
                $this->log(ResCode::COLLECTION_UPDATE_FAIL);
                return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
            }
            Db::commit();
            return $this->res();
        } catch (Exception $e) {
            Db::rollback();
            $this->logException($e->getMessage());
            return $this->exception();
        }
    }
}