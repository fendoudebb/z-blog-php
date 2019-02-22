<?php

namespace app\common\util;


class Mongo {

    private static $handler = null;

    private static $options = [
        'host' => '127.0.0.1',
        'port' => 27017,
        'username' => '',
        'password' => 0,
    ];

    private static function init() {
        if (!isset(self::$handler)) {
            if (is_null(self::$handler)) {
                if (!extension_loaded('mongodb')) {
                    throw new \BadFunctionCallException('not support: mongodb');
                }
                self::$handler = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
            }
        }
        return self::$handler;
    }

    public static function insert($col,$data) {
        $bulk = new \MongoDB\Driver\BulkWrite();
        $bulk->insert($data);
        return self::init()->executeBulkWrite($col, $bulk);
    }

}