<?php

namespace app\admin\controller;

use app\common\config\ResCode;
use think\Controller;
use think\Log;
use think\Request;

abstract class Base extends Controller {

    public function _initialize() {
        $request = Request::instance();
        $date = date('Y-m-d H:i:s', time());
        $ip = $request->ip();
        $url = $request->url();
        $memory_use = number_format((memory_get_usage() - THINK_START_MEM) / 1024 / 1024, 2);
        $param = $request->param();
        Log::log("[$date] : ip->[$ip], url->[$url], memory->[$memory_use mb], request param -> ". json_encode($param));
    }

    private function base($code, $data = null, $msg = '') {
        $return_data = [
            'code' => $code,
            'msg' => empty($msg) ? ResCode::$res_code[$code] : $msg,
        ];
        if (isset($data)) {
            $return_data['data'] = $data;
        }
        return $return_data;
    }

    public function res($data = null) {
        return json(self::base(ResCode::REQUEST_SUCCESS, $data));
    }

    public function fail($code) {
        return json(self::base($code));
    }

    public function validFail($msg) {
        return json(self::base(ResCode::FORBIDDEN, null, $msg));
    }

    public function exception() {
        return json(self::base(ResCode::INTERNAL_SEVER_ERROR));
    }

}

