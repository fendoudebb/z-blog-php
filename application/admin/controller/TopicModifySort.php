<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class TopicModifySort extends BaseRoleAdmin {

    public function modifyTopicSort() {
        $topicId = input('post.topicId');
        $topicSort = input('post.topicSort');
        if (!isset($topicId)) {
            Log::log("modify topic sort, missing params: topic id. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_ID);
        }
        if (!isset($topicSort)) {
            Log::log("modify topic sort, missing params: topic sort. operator[$this->username]");
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_SORT);
        }
        if (!is_numeric($topicId)) {
            Log::log("modify topic sort, illegal argument: topic id. operator[$this->username]");
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        if (!is_numeric($topicSort)) {
            Log::log("modify topic sort, illegal argument: topic sort. operator[$this->username]");
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
                Log::log("modify topic sort, update table fail. operator[$this->username]");
                return $this->fail(ResCode::TABLE_UPDATE_FAIL);
            }
            Db::commit();
            return $this->res();
        } catch (Exception $e) {
            Db::rollback();
            Log::log("modify topic sort, operator[$this->username], exception->" . $e->getMessage());
            return $this->exception();
        }
    }
}