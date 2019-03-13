<?php

namespace app\index\controller;


use app\common\config\RedisKey;
use app\common\config\ResCode;
use app\common\util\Parser;
use app\common\util\Redis;
use app\index\util\RankInfo;
use think\Db;
use think\Exception;
use think\Log;

class Post extends Base {

    public function post($postId) {
        $postCmd = [
            'find' => 'post',
            'filter' => [
                'postId' => intval($postId),
            ],
            'projection' => [
                '_id' => 0,
                'title' => 1,
                'keywords' => 1,
                'description' => 1,
                'postTime' => 1,
                'contentHtml' => 1,
                'postStatus' => 1,
                'commentStatus' => 1,
                'postProp' => 1,
                'topics' => 1,
                'pv' => 1,
                'commentCount' => 1,
                'likeCount' => 1
            ],
            'limit' => 1
        ];
        $postCmdArr = Db::cmd($postCmd);
        Log::log(json_encode($postCmdArr));
        if (empty($postCmdArr)) {
            $this->log(ResCode::POST_DOES_NOT_EXIST);
            return redirect('/404.html');
        }
        $post = $postCmdArr[0];
        $postStatus = $post['postStatus'];
        if ($postStatus !== 'ONLINE') {
            $this->log(ResCode::POST_IS_NOT_ONLINE);
            return redirect('/404.html');
        }

        $arr = [
            'title' => $post['title'],
            'keywords' => $post['keywords'],
            'description' => $post['description'],
            'post' => $post,
        ];
        $rankInfo = new RankInfo();
        $arr = array_merge($arr, $rankInfo->rankInfo());

        $compressHtml = compressHtml($this->fetch('post', $arr));
        return $compressHtml;
    }

}