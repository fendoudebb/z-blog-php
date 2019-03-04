<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use MongoDB\BSON\ObjectId;
use think\Db;
use think\Exception;
use think\Log;

class PostTopicAdd extends Base {

    public function addPostTopic() {
        $postId = input('post.postId');
        $topic = input('post.topic');
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (!isset($topic)) {
            $this->log(ResCode::MISSING_PARAMS_TOPIC);
            return $this->fail(ResCode::MISSING_PARAMS_TOPIC);
        }
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if (empty($topic)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_TOPIC);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_TOPIC);
        }

        $postTopicCmd = [
            'find' => 'post',
            'filter' => [
                '_id' => new ObjectId($postId)
            ],
            'projection' => [
                'topics' => 1
            ],
            'limit' => 1
        ];
        $postTopicArr = Db::cmd($postTopicCmd);
        Log::log(json_encode($postTopicArr));
        var_dump($postTopicArr);
        /*try {
            Db::startTrans();
            $isPostExists = Db::table('post')
                ->field('id')
                ->where('id', $postId)
                ->find();
            if (!$isPostExists) {
                Db::rollback();
                $this->log(ResCode::POST_DOES_NOT_EXIST);
                return $this->fail(ResCode::POST_DOES_NOT_EXIST);
            }
            $isTopicExists = Db::table('topic')
                ->field('id')
                ->where('id', $topicId)
                ->find();
            if (!$isTopicExists) {
                Db::rollback();
                $this->log(ResCode::TOPIC_ID_DOES_NOT_EXIST);
                return $this->fail(ResCode::TOPIC_ID_DOES_NOT_EXIST);
            }
            $postTopicCount = Db::table('post_topic')
                ->where('post_id', $postId)
                ->where('topic_id', $topicId)
                ->where('is_delete', 0)
                ->count();
            if ($postTopicCount >= 3) {
                Db::rollback();
                $this->log(ResCode::OVER_POST_TOPIC_COUNT);
                return $this->fail(ResCode::OVER_POST_TOPIC_COUNT);
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
                    $this->log(ResCode::POST_TOPIC_ALREADY_EXIST);
                    return $this->fail(ResCode::POST_TOPIC_ALREADY_EXIST);
                }
                $updateResult = Db::table('post_topic')
                    ->where('id', $id)
                    ->update([
                        'is_delete' => 0
                    ]);
                if (!$updateResult) {
                    Db::rollback();
                    $this->log(ResCode::COLLECTION_UPDATE_FAIL);
                    return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
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
                    $this->log(ResCode::COLLECTION_INSERT_FAIL);
                    return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
                }
                Db::commit();
                return $this->res();
            }
        } catch (Exception $e) {
            Db::rollback();
            $this->logException($e->getMessage());
            return $this->exception();
        }*/

    }

}