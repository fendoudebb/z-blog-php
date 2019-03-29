<?php

namespace app\index\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\UTCDateTime;

class MessageLeave extends Base {

    public function leaveMessage() {
        $nickname = strval(input("post.nickname"));
        $content = strval(input("post.content"));
        $nickname = htmlspecialchars($nickname, ENT_NOQUOTES);
        $content = htmlspecialchars($content, ENT_NOQUOTES);
        $findMaxFloorCmd = [
            'find' => 'comment',
            'sort' => [
                'floor' => -1
            ],
            'projection' => [
                '_id' => 0,
                'floor' => 1
            ],
            'limit' => 1,
        ];
        $cmdArr = Mongo::cmd($findMaxFloorCmd);
        if (empty($cmdArr)) {
            $floor = 1;
        } else {
            if (property_exists($cmdArr[0],'floor')) {
                $floor = $cmdArr[0]->floor + 1;
            } else {
                $floor = 1;
            }
        }

        $commentTime = new UTCDateTime();

        $document = [
            'content' => $content,
            'nickname' => $nickname,
            'floor' => $floor,
            'commentTime' => $commentTime,
            'status' => 'ONLINE',
            'ip' => $this->ip,
            'userAgent' => $this->userAgent,
        ];

        if (ini_get("browscap")) {
            $userAgentParseResult = get_browser($this->userAgent, true);
            $document['browser'] = $userAgentParseResult['parent'];
            $document['os'] = $userAgentParseResult['platform'];
        }

        $insertPostCmd = [
            'insert' => 'comment',
            'documents' => [
                $document
            ]
        ];
        $insertPostResult = Mongo::cmd($insertPostCmd);
        if (empty($insertPostResult) || !$insertPostResult[0]->ok) {
            $this->log(ResCode::COLLECTION_INSERT_FAIL);
            return json([
                'code' => -1,
                'msg' => 'fail'
            ]);
        }
        return json(['code' => 200, 'msg' => 'ok']);
    }

}