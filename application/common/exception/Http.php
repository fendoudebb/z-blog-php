<?php

namespace app\common\exception;

use app\admin\config\ResCode;
use Exception;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\ValidateException;
use think\Log;

class Http extends Handle {

    public function render(Exception $e) {

        // 参数验证错误
        if ($e instanceof ValidateException) {
            Log::info("valid exception -> " . $e->getMessage());
            return self::fail(ResCode::VALID_FAIL, $e->getMessage());
        }

        // 参数验证错误
        if ($e instanceof UnKnownException) {
            Log::info("UnKnown exception -> " . $e->getMessage());
            return self::fail($e->getMessage());
        }

        if ($e instanceof HttpException) {
            Log::info("http exception -> " . $e->getMessage());
            return self::fail(ResCode::URL_NOT_EXIST);
        }

        //TODO::开发者对异常的操作
        //可以在此交由系统处理
//        return parent::render($e);
        return self::fail(ResCode::INTERVAL_EXCEPTION);
    }

    public function fail($code, $msg = '') {
        $return_data = [
            'code' => $code,
            'msg' => empty($msg) ? ResCode::$res_code[$code] : $msg,
        ];
        return json($return_data);
    }

}