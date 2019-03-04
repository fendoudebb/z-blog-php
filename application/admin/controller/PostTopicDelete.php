<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class PostTopicDelete extends BaseRoleAdmin {

    public function deletePostTopic() {
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

        /*try {
            Db::startTrans();
            $postTopic = Db::table('post_topic')
                ->field('id, is_delete')
                ->where('post_id', $postId)
                ->where('topic_id', $topic)
                ->find();
            if (!isset($postTopic)) {
                Db::rollback();
                $this->log(ResCode::POST_TOPIC_DOES_NOT_EXIST);
                return $this->fail(ResCode::POST_TOPIC_DOES_NOT_EXIST);
            }

            $id = $postTopic['id'];
            $isDelete = $postTopic['is_delete'];
            if ($isDelete) {
                Db::rollback();
                $this->log(ResCode::POST_TOPIC_HAS_BEEN_DELETED);
                return $this->fail(ResCode::POST_TOPIC_HAS_BEEN_DELETED);
            }
            $updateResult = Db::table('post_topic')
                ->where('id', $id)
                ->update([
                    'is_delete' => 1
                ]);
            if (!$updateResult) {
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
        }*/

    }

}