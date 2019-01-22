<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;
use think\Log;

class PostAddTopic extends BaseRoleAdmin {

    public function addPostTopic() {
        $postId = input('post.postId');
        $topicId = input('post.topicId');
        if (!isset($postId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (!is_numeric($postId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if (!isset($topicId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::MISSING_PARAMS_TOPIC_ID);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC_ID);
        }
        if (!is_numeric($topicId)) {
            Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC_ID);
        }
        try {
            Db::startTrans();
            $isPostExists = Db::table('post')
                ->field('id')
                ->where('id', $postId)
                ->find();
            if (!$isPostExists) {
                Db::rollback();
                Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::POST_ID_DOES_NOT_EXIST);
                return $this->fail(ResCode::POST_ID_DOES_NOT_EXIST);
            }
            $isTopicExists = Db::table('topic')
                ->field('id')
                ->where('id', $topicId)
                ->find();
            if (!$isTopicExists) {
                Db::rollback();
                Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::TOPIC_ID_DOES_NOT_EXIST);
                return $this->fail(ResCode::TOPIC_ID_DOES_NOT_EXIST);
            }
            $postTopic = Db::table('post_topic')
                ->field('id, is_delete')
                ->where('post_id', $postId)
                ->where('topic_id', $topicId)
                ->find();
            if (isset($postTopic)) {
                $id = $postTopic['id'];
                $isDelete = $postTopic['is_delete'];
                if ($isDelete === 0) {
                    Db::rollback();
                    Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::POST_TOPIC_ALREADY_EXIST);
                    return $this->fail(ResCode::POST_TOPIC_ALREADY_EXIST);
                }
                $updateResult = Db::table('post_topic')
                    ->where('id', $id)
                    ->update([
                        'is_delete' => 0
                    ]);
                if (!$updateResult) {
                    Db::rollback();
                    Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::TABLE_UPDATE_FAIL);
                    return $this->fail(ResCode::TABLE_UPDATE_FAIL);
                }
                Db::commit();
                return $this->res();
            } else {
                $insertResult = Db::table('post_topic')
                    ->insert([
                        'post_id' => $postId,
                        'topic_id' => $topicId
                    ]);
                if (!$insertResult) {
                    Db::rollback();
                    Log::log(__FUNCTION__ . "-operator[$this->username]: " . ResCode::TABLE_INSERT_FAIL);
                    return $this->fail(ResCode::TABLE_INSERT_FAIL);
                }
                Db::commit();
                return $this->res();
            }
        } catch (Exception $e) {
            Db::rollback();
            Log::log(__FUNCTION__ . "-operator[$this->username]: exception-> " . $e->getMessage());
            return $this->exception();
        }

    }

}