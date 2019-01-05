<?php

namespace app\common\exception;

use app\common\config\ResCode;
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
            return self::fail(ResCode::UNAUTHORIZED, $e->getMessage());
        }

        // 参数验证错误
        if ($e instanceof SystemException) {
            Log::info("system exception -> " . $e->getMessage());
            return self::fail($e->getMessage());
        }

        if ($e instanceof HttpException) {
            Log::info("http exception -> " . $e->getMessage());
            return self::fail(ResCode::URL_NOT_EXIST);
        }

        //TODO::开发者对异常的操作
        //可以在此交由系统处理
//        return parent::render($e);
        return self::fail(ResCode::INTERNAL_SEVER_ERROR);
    }

    public function fail($code, $msg = '') {
        $return_data = [
            'code' => $code,
            'msg' => empty($msg) ? ResCode::$res_code[$code] : $msg,
        ];
        return json($return_data);
    }

}