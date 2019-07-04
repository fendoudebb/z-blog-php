<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class LinksAudit extends BaseRoleAdmin {

    public function auditLink() {
        $linkId = strval(input('post.linkId'));
        $status = strval(input('post.status'));
        if (strlen($linkId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_LINK_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_LINK_ID);
        }
        if ($status !== 'ONLINE' && $status != 'OFFLINE') {
            $this->log(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_AUDIT_STATUS);
        }
        $cmd = [
            'update' => 'links',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($linkId),
                    ],
                    'u' => [
                        '$set' => [
                            'status' => $status
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
        return $this->res();
    }

}