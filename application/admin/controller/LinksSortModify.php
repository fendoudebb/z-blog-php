<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class LinksSortModify extends BaseRoleAdmin {

    public function modifyTopicSort() {
        $linkId = strval(input('post.linkId'));
        $sort = intval(input('post.sort'));
        if (strlen($linkId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_LINK_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_LINK_ID);
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
                            'sort' => $sort
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