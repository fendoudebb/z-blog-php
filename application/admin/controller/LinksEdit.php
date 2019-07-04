<?php


namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;

class LinksEdit extends BaseRoleAdmin {

    public function editLink() {
        $linkId = strval(input('post.linkId'));
        $link = trim(strval("post.link"));
        $websiteName = trim(strval("post.websiteName"));
        $owner = trim(strval("post.owner"));
        $ownerEmail = trim(strval("post.ownerEmail"));

        if (strlen($linkId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_LINK_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_LINK_ID);
        }
        if (strlen($link) <= 0) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_LINK);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_LINK);
        }
        if (strlen($websiteName) <= 0) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_WEBSITE_NAME);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_WEBSITE_NAME);
        }
        if (strlen($owner) <= 0) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_LINK_OWNER);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_LINK_OWNER);
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
                            'websiteName' => $websiteName,
                            'link' => $link,
                            'owner' => $owner,
                            'ownerEmail' => $ownerEmail,
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