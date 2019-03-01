<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class TopicModifyName extends BaseRoleAdmin {

    public function modifyTopicName() {
        $topicId = input('post.topicId');
        $topicName = input('post.topicName');
        if (!isset($topicId)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_ID);
        }
        if (!isset($topicName)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC_NAME);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_NAME);
        }
        if (!is_numeric($topicId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        try {
            Db::startTrans();
            $existId = Db::table('topic')
                ->where('name', $topicName)
                ->value('id');
            if (isset($existId)) {
                Db::rollback();
                $this->log(ResCode::TOPIC_NAME_ALREADY_EXISTS);
                return $this->fail(ResCode::TOPIC_NAME_ALREADY_EXISTS);
            }
            $updateNameResult = Db::table('topic')
                ->where('id', $topicId)
                ->update([
                    'name' => $topicName
                ]);
            if (!$updateNameResult) {
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