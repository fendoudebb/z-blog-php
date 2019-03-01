<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class TopicModifySort extends BaseRoleAdmin {

    public function modifyTopicSort() {
        $topicId = input('post.topicId');
        $topicSort = input('post.topicSort');
        if (!isset($topicId)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_ID);
        }
        if (!isset($topicSort)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC_SORT);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_SORT);
        }
        if (!is_numeric($topicId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        if (!is_numeric($topicSort)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_SORT);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_SORT);
        }
        try {
            Db::startTrans();
            $updateSortResult = Db::table('topic')
                ->where('id', $topicId)
                ->update([
                    'sort' => $topicSort
                ]);
            if (!$updateSortResult) {
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