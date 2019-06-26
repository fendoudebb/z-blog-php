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

    public function formatTimestamp() {
        $arr = [
            'title' => '时间戳转换',
            'keywords' => '时间戳转换，转换时间戳，格式化时间戳，时间戳格式化，时间戳',
            'description' => '时间戳转换，timestamp',
        ];
        return compressHtml($this->fetch('tool/format_timestamp', $arr));
    }

    public function queryIp() {
        $arr = [
            'title' => 'IP查询',
            'keywords' => 'IP查询，查询IP，IP归属地',
            'description' => 'IP查询，查询IP，IP归属地',
        ];
        return compressHtml($this->fetch('tool/query_ip', $arr));
    }

}