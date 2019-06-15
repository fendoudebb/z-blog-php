<?php

namespace app\common\exception;

use app\common\config\ResCode;
use Exception;
use think\exception\Handle;
use think\exception\RouteNotFoundException;
use think\Log;
use think\Request;

class Http extends Handle {

    public function render(Exception $e) {

        // 手动抛出异常
        if ($e instanceof SystemException) {
            Log::info("system exception -> " . $e->getMessage());
            return self::fail($e->getMessage());
        }

        if ($e instanceof RouteNotFoundException) {
            Log::info("RouteNotFoundException -> " . $e->getMessage());
            $url = Request::instance()->url();
            if (strpos($url, '/admin/') === 0) {
                return self::fail(ResCode::URL_NOT_EXIST);
            }
//            return self::fail(ResCode::URL_NOT_EXIST);
//            return view('index@public/404');//跨模块调用
            return redirect('/404.html');//重定向到404页面
        }

        //TODO::开发者对异常的操作
        //可以在此交由系统处理
//        return parent::render($e);
        return self::fail(ResCode::INTERNAL_SEVER_ERROR, $e->getMessage());
    }

    public function fail($code, $msg = '') {
        $return_data = [
            'code' => $code,
            'msg' => empty($msg) ? ResCode::$res_code[$code] : $msg,
        ];
        return json($return_data);
    }

}