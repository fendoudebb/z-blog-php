<?php

namespace app\admin\controller;

// 网页链接，网站名称，站长名称，站长邮箱

use app\common\config\ResCode;
use app\common\util\Mongo;

class LinksAdd extends BaseRoleAdmin {

    public function addLink() {
        $link = trim(strval("post.link"));
        $websiteName = trim(strval("post.websiteName"));
        $owner = trim(strval("post.owner"));
        $ownerEmail = trim(strval("post.ownerEmail"));

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

        $findMaxSortLinkCmd = [
            'find' => 'links',
            'sort' => [
                'sort' => -1
            ],
            'projection' => [
                '_id' => 0,
                'sort' => 1
            ],
            'limit' => 1,
        ];
        $cmdArr = Mongo::cmd($findMaxSortLinkCmd);
        if (empty($cmdArr)) {
            $sort = 1;
        } else {
            if (property_exists($cmdArr[0],'sort')) {
                $sort = $cmdArr[0]->sort + 1;
            } else {
                $sort = 1;
            }
        }

        $insertLinkCmd = [
            'insert' => 'links',
            'documents' => [
                [
                    'websiteName' => $websiteName,
                    'link' => $link,
                    'owner' => $owner,
                    'ownerEmail' => $ownerEmail,
                    'sort' => $sort,
                    'status' => 'ONLINE'
                ]
            ]
        ];
        $insertLinkResult = Mongo::cmd($insertLinkCmd);
        if (empty($insertLinkResult) || !$insertLinkResult[0]->ok) {
            $this->log(ResCode::COLLECTION_INSERT_FAIL);
            return $this->fail(ResCode::COLLECTION_INSERT_FAIL);
        }
        return $this->res();


    }

}