<?php

namespace app\index\controller;


use app\common\util\Mongo;

class English extends Base {

    public function english() {
        $page = intval(input('get.page'));
        if ($page < 1) {
            $page = 1;
        }
        $size = 20;
        $offset = ($page - 1) * $size;
        $arr = [
            'title' => '英语角',
            'keywords' => '英语角，IT英语',
            'description' => '英语角，IT英语',
            'currentPage' => $page,
        ];
        $indexEnglishCmd = [
            'find' => 'english',
            'projection' => [
                '_id' => 0,
                'word' => 1,
                'english_phonetic' => 1,
                'america_phonetic' => 1,
                'translation' => 1,//数组：property, explanation
                'example_sentence' => 1,
                'sentence_translation' => 1
            ],
            'sort' => [
                '_id' => -1
            ],
            'skip' => $offset,
            'limit' => $size
        ];
        $indexEnglishCmdArr = Mongo::cmd($indexEnglishCmd);
        $arr['english'] = $indexEnglishCmdArr;
        $countEnglishCmd = [
            'count' => 'english',
        ];
        $countEnglishCmdArr = Mongo::cmd($countEnglishCmd);
        $arr['totalPage'] = ceil($countEnglishCmdArr[0]->n / $size);
        $compressHtml = compressHtml($this->fetch('english/english', $arr));
        return $compressHtml;
    }


}