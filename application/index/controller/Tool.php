<?php

namespace app\index\controller;


class Tool extends Base {

    public function formatJson() {
        $arr = [
            'title' => 'JSON格式化工具',
            'keywords' => '格式化json,解析json,压缩json,格式化,解析,压缩',
            'description' => '格式化json,解析json,压缩json,格式化,解析,压缩',
        ];
        return compressHtml($this->fetch('tool/format_json', $arr));
    }

}