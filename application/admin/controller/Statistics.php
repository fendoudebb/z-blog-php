<?php

namespace app\admin\controller;


use think\Db;
use think\Exception;

class Statistics extends BaseRoleAdmin {

    public function statistics() {
        try {
            $stat = Db::table('statistics')
                ->field('name, count, update_time')
                ->select();
            return $this->res($stat);
        } catch (Exception $e) {
            $this->logException($e->getMessage());
            return $this->exception();
        }
    }

}