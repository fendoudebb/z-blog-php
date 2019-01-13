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
                    'user_id' => $a++,
                    'status' => $a % 4,
                    'title' => '测试title' . $a,
                    'keywords' => '测试keywords' . $a,
                    'description' => '测试description' . $a,
                    'is_private' => $a % 2,

                ];
                /*$data = [
                    'username' => 'test'.$a++
                ];*/
                $dataSet[] = $data;
            }
            Db::table('post')
                ->insertAll($dataSet);
            Db::commit();
        }
        $end = time();
        return $end - $start;
    }

}