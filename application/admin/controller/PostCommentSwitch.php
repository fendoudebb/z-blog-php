<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class PostCommentSwitch extends BaseRoleAdmin {

    public function switchPostComment() {
        $postId = input('post.postId');
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (!is_numeric($postId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        try {
            Db::startTrans();
            $isPostExists = Db::table('post')
                ->field('id')
                ->where('id', $postId)
                ->find();
            if (!$isPostExists) {
                Db::rollback();
                $this->log(ResCode::POST_ID_DOES_NOT_EXIST);
                return $this->fail(ResCode::POST_ID_DOES_NOT_EXIST);
            }
            $updateResult = Db::table('post')
                ->where('id', $postId)
                ->update([
                    'is_comment_close' => Db::raw('ABS(1 - is_comment_close)')
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
        }

    }

}