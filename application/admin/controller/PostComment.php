<?php

namespace app\admin\controller;


use app\common\config\ResCode;
use think\Db;
use think\Exception;

class PostComment extends BaseRoleAdmin {

    public function postComment() {
        $postId = input('post.postId');
        $page = input('post.page');
        $size = input('post.size');
        if (!isset($postId)) {
            $this->log(ResCode::MISSING_PARAMS_POST_ID);
            return $this->fail(ResCode::MISSING_PARAMS_POST_ID);
        }
        if (!is_numeric($postId)) {
            $this->log(ResCode::ILLEGAL_ARGUMENT_POST_ID);
            return $this->fail(ResCode::ILLEGAL_ARGUMENT_POST_ID);
        }
        if (!isset($page) || !is_numeric($page) || $page < 1) {
            $page = 1;
        }
        if (!isset($size) || !is_numeric($size) || $size < 1 || $size > 20) {
            $size = 20;
        }

        try {
            $response = [
                'currentPage' => $page,
                'pageSize' => $size,
            ];
            $count = Db::table('comment')
                ->where('post_id', $postId)
                ->count();
            $comment = Db::table('comment')
                ->field('id as commentId, is_delete as isDelete, parent_id as parentId, post_date as postDate, like_count as likeCount,
                author, author_email as authorEmail, author_ip as authorIp, author_user_agent as authorUserAgent')
                ->where('post_id', $postId)
                ->order('postDate', 'desc')
                ->limit($page * $size ,$size)
                ->select();
            $response['totalCount'] = $count;
            //ceil向上取整, floor向下取整, php中除法为具体结果不会舍弃小数点
            $response['totalPage'] = ceil($count / $size);
            $response['comment'] = $comment;
            return $this->res($response);
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return $this->exception();
        }

    }

}