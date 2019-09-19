<?php

namespace app\index\controller;


use app\common\util\Mongo;

class MessageBoard extends Base {

    public function messageBoard() {
        $page = intval(input('get.page'));
        if ($page < 1) {
            $page = 1;
        }
        $size = 20;
        $offset = ($page - 1) * $size;
        $arr = [
            'title' => '留言板',
            'keywords' => '留言板',
            'description' => '留言板',
            'currentPage' => $page,
        ];
        $indexCommentsCmd = [
            'find' => 'comment',
            'projection' => [
                '_id' => 0,
                'content' => 1,
                'nickname' => 1,
                'floor' => 1,
                'status' => 1,
                'commentTime' => 1,
                'browser' => 1,
                'os' => 1,
                'ip' => 1,
                'replies' => 1
            ],
            'sort' => [
                'commentTime' => -1
            ],
            'skip' => $offset,
            'limit' => $size
        ];
        $indexCommentsCmdArr = Mongo::cmd($indexCommentsCmd);
        $arr['comments'] = $indexCommentsCmdArr;
        $countCommentsCmd = [
            'count' => 'comment',
        ];
        $countCommentsCmdArr = Mongo::cmd($countCommentsCmd);
        $arr['totalPage'] = ceil($countCommentsCmdArr[0]->n / $size);
        $compressHtml = compressHtml($this->fetch('message_board/message_board', $arr));
        return $compressHtml;
    }

}