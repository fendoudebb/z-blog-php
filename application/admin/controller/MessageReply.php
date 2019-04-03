<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\Request;

class MessageReply extends BaseRoleAdmin {

    public function replyMessage() {
        $commentId = strval(input('post.commentId'));
        $content = strval(input("post.content"));
        $content = htmlspecialchars($content, ENT_NOQUOTES);
        if (strlen($commentId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
        }

        $commentTime = new UTCDateTime();
        $userAgent = Request::instance()->header('user-agent');
        $document = [
            'content' => $content,
            'nickname' => 'Z',
            'commentTime' => $commentTime,
            'status' => 'ONLINE',
            'ip' => $this->ip,
            'userAgent' => $userAgent,
        ];

        if (ini_get("browscap")) {
            $userAgentParseResult = get_browser($userAgent, true);
            $document['browser'] = $userAgentParseResult['parent'];
            $document['os'] = $userAgentParseResult['platform'];
        }

        $messageReplyCmd = [
            'update' => 'comment',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($commentId)
                    ],
                    'u' => [
                        '$push' => [
                            'reply' => $document
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ]

        ];
        Mongo::cmd($messageReplyCmd);
        return $this->res();
    }

}