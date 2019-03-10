<?php

namespace app\index\controller;

class Index extends Base {

    public function index() {
        $page = intval(input('get.page'));
        if ($page < 1) {
            $page = 0;
        }
        $size = 20;
        $offset = ($page - 1) * $size;
        $arr = [
            'title' => '麦司机 - 追上寄予厚望的自己',
            'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，麦司机',
            'description' => 'Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术博客',
            'currentPage' => $page,
            'pageSize' => $size,
            'totalPage' => ceil('' / $size),
            'post' => ''
        ];
        if (!$this->isMobile) {
            $arr['pvRank'] = '';
            $arr['commentRank'] = '';
            $arr['likeRank'] = '';
        }

        $compressHtml = compressHtml($this->fetch('index', $arr));
        return $compressHtml;


    }

}
