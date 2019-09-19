<?php


namespace app\admin\controller;


use app\common\config\ResCode;
use app\common\util\Mongo;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use think\Request;

class PostCommentReply extends BaseRoleAdmin {

    public function replyPostComment() {
        $postId = strval(input('post.postId'));
        $commentId = strval(input('post.commentId'));
        $replyContent = htmlspecialchars(strval(input("post.replyContent")), ENT_NOQUOTES);
        if (strlen($postId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if (strlen($commentId) !== 24) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_COMMENT_ID);
        }
        if (strlen($replyContent) <= 0) {
            $this->log(ResCode::REPLY_CONTENT_IS_EMPTY);
            return $this->fail(ResCode::REPLY_CONTENT_IS_EMPTY);
        }

        $commentTime = new UTCDateTime();

        $userAgent = Request::instance()->header('user-agent');
        $document = [
            'replyContent' => $replyContent,
            'replyTime' => $commentTime,
            'ip' => $this->ip,
            'userAgent' => $userAgent,
        ];

        if (ini_get("browscap")) {
            $userAgentParseResult = get_browser($userAgent, true);
            $document['browser'] = $userAgentParseResult['parent'];
            $document['os'] = $userAgentParseResult['platform'];
        }

        $cmd = [
            'update' => 'post',
            'updates' => [
                [
                    'q' => [
                        '_id' => new ObjectId($postId),
                        'postComment.commentId' => new ObjectId($commentId)
                    ],
                    'u' => [
                        '$push' => [
                            'postComment.$.replies' => $document
                        ],
                        '$currentDate' => [
                            'lastModified' => true
                        ],
                    ]
                ]
            ],
        ];
        Mongo::cmd($cmd);
        return $this->res();
    }

}