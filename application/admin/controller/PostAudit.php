<?php

namespace app\admin\controller;


use app\common\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Redis;
use MongoDB\BSON\ObjectId;
use think\Db;

class PostAudit extends BaseRoleAdmin {

    public function auditPost() {
        $postId = input('post.postId');
        $auditStatus = strval(input('post.auditStatus'));
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if ($auditStatus !== 'ONLINE' && $auditStatus != 'OFFLINE') {
            $this->log(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
        }
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        $cmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($postId),
                    ],
                    'u' => [
                        '$set' => [
                            'postStatus' => $auditStatus
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ],
        ];
        $modifyResult = Db::cmd($cmd);
        if (empty($modifyResult) || !$modifyResult[0]['ok']) {
            $this->log(ResCode::COLLECTION_UPDATE_FAIL);
            return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
        }
        if ($auditStatus === 1) {//上线
            Redis::init()->sAdd(RedisKey::SET_VISIBLE_POST, $postId);
        } else {//下线
            Redis::init()->sRem(RedisKey::SET_VISIBLE_POST, $postId);
        }
        return $this->res();
    }

}