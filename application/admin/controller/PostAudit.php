<?php

namespace app\admin\controller;


use app\common\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\ElasticsearchUtil;
use app\common\util\Mongo;
use app\common\util\Redis;

/*
Elasticsearch 更新部分字段
POST /post/_update/17
{
  "doc": {
    "offline": true
  }
}
*/
class PostAudit extends BaseRoleAdmin {

    public function auditPost() {
        $postId = intval(input('post.postId'));
        $auditStatus = strval(input('post.auditStatus'));
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if ($auditStatus !== 'ONLINE' && $auditStatus != 'OFFLINE') {
            $this->log(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
        }
        $cmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        'postId' => $postId,
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
        $modifyResult = Mongo::cmd($cmd);
        if (empty($modifyResult) || !$modifyResult[0]->ok) {
            $this->log(ResCode::COLLECTION_UPDATE_FAIL);
            return $this->fail(ResCode::COLLECTION_UPDATE_FAIL);
        }

        $param = [
            "doc" => [
                "offline" => ($auditStatus === 'OFFLINE')
            ]
        ];
        ElasticsearchUtil::POST("http://localhost:9200/post/_update/" . $postId, $param);

        $pipeline = Redis::init()->multi(\Redis::PIPELINE);

        if ($auditStatus === 1) {//上线
            $pipeline->sAdd(RedisKey::SET_VISIBLE_POST, $postId);
        } else {//下线
            $pipeline->sRem(RedisKey::SET_VISIBLE_POST, $postId);
        }
        $pipeline->del(RedisKey::SITEMAP_XML);
        $pipeline->del(RedisKey::SITEMAP_XML_GOOGLE);
        $pipeline->exec();
        return $this->res();
    }

}