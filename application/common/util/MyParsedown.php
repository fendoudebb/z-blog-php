<?php

namespace app\common\util;


class MyParsedown extends Parsedown {

    protected $currentHost = 'www.zhangbj.com';

    protected function inlineUrl($Excerpt) {
        return $this->outsiteLink(parent::inlineUrl($Excerpt));
    }

    protected function inlineUrlTag($Excerpt) {
        return $this->outsiteLink(parent::inlineUrlTag($Excerpt));
    }

    protected function inlineLink($Excerpt) {
        return $this->outsiteLink(parent::inlineLink($Excerpt));
    }

    protected function outsiteLink($block) {
        if (!empty($block)) {
            $url = $block['element']['attributes']['href'];
            $host = parse_url($url, PHP_URL_HOST);

            if (strstr($host, $this->currentHost) === false) {
                $block['element']['attributes']['target'] = '_blank';
            }
        }
        return $block;
    }

}