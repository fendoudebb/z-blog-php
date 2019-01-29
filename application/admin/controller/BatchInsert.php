<?php

namespace app\admin\controller;


use think\Db;

class BatchInsert extends BaseRoleAdmin {

    public function batchInsert() {
        $start = time();
        $a = 1;
        for ($i = 0; $i < 10; $i++) {
            Db::startTrans();
            $dataSet = [];
            for ($j = 0; $j < 10000; $j++) {
                $data = [
                    'post_id' => $j % 100,
                    'content' => 'aaa测试' . $j,
                    'author' => '张三' . $j,
                ];
                /*$data = [
                    'username' => 'test'.$a++
                ];*/
                $dataSet[] = $data;
            }
            Db::table('comment')
                ->insertAll($dataSet);
            Db::commit();
        }
        $end = time();
        return $end - $start;
    }

}