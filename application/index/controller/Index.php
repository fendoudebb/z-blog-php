<?php

namespace app\index\controller;

class Index extends Base {

    public function index() {
        $arr = [
            'title' => '麦司机的个人博客',
            'keywords' => 'Java，PHP，Android，Vue.js，MySQL，Redis，Linux，移动互联网，技术博客，麦司机',
            'description' => 'Java，PHP，Android，Vue.js，Linux，Nginx，MySQL，Redis，NoSQL，Git，JavaScript，HTML，CSS，Markdown，Python，Mac等各类互联网技术博客',
            'now' => time(),
            'ip' => $this->ip
        ];
        return compressHtml($this->fetch('index', $arr));
    }

}
