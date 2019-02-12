<?php

namespace app\admin\controller;


use app\common\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Redis;
use think\Db;
use think\Exception;

class PostAudit extends BaseRoleAdmin {

    public function auditPost() {
        $postId = input('post.postId');
        $auditStatus = input('post.auditStatus');
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (!is_numeric($postId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if (!isset($auditStatus)) {
            $this->log(ResCode::MISSING_PARAMS_AUDIT_STATUS);
            return $this->fail(ResCode::MISSING_PARAMS_AUDIT_STATUS);
        }
        if (!is_numeric($auditStatus) || $auditStatus < 1 || $auditStatus > 2) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
        }
        try {
            $updateResult = Db::table('post')
                ->where('id', $postId)
                ->update([
                    'status' => $auditStatus
                ]);
            if (!$updateResult) {
                $this->log(ResCode::TABLE_UPDATE_FAIL);
                return $this->fail(ResCode::TABLE_UPDATE_FAIL);
            }
            if ($auditStatus === 1) {//上线
                Redis::init()->sAdd(RedisKey::SET_VISIBLE_POST, $postId);
            } else {//下线
                Redis::init()->sRem(RedisKey::SET_VISIBLE_POST, $postId);
            }
            return $this->res();
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return $this->exception();
        }

    }

}