<?php

namespace app\common\util;


class Redis {

    private static $handler = null;

    private static $options = [
        'host' => '127.0.0.1',
        'port' => 6379,
        'password' => '',
        'select' => 0,
        'timeout' => 0,
        'expire' => 0,
        'persistent' => true,
        'prefix' => '',
    ];

    public static function init() {
        if (!isset(self::$handler)) {
            if (is_null(self::$handler)) {
                if (!extension_loaded('redis')) {
                    throw new \BadFunctionCallException('not support: redis');
                }
                self::$handler = new \Redis;
                if (self::$options['persistent']) {
                    self::$handler->pconnect(self::$options['host'], self::$options['port'], self::$options['timeout'], 'persistent_id_' . self::$options['select']);
                } else {
                    self::$handler->connect(self::$options['host'], self::$options['port'], self::$options['timeout']);
                }

                if ('' != self::$options['password']) {
                    self::$handler->auth(self::$options['password']);
                }

                if (0 != self::$options['select']) {
                    self::$handler->select(self::$options['select']);
                }
            }
        }
        return self::$handler;
    }


    /*public function set($key, $value, $expire) {
        return self::init()->set($key, $value, $expire);
    }

    public function get($key) {
        return self::init()->get($key);
    }

    public function del($key) {
        return self::init()->del($key);
    }

    // -----------------------SortedSet Start-----------------------
    public function zAdd($key, $score, $member) {
        return self::init()->zAdd($key, $score, $member);
    }

    public function zCard($key) {
        return self::init()->zCard($key);
    }

    public function zRem($key, $member) {
        return self::init()->zRem($key, $member);
    }

    public function zRevRangeByScore($key, $start, $end, array $options = array()) {
        return self::init()->zRevRangeByScore($key, $start, $end, $options);
    }

    public function zScore($key, $member) {
        return self::init()->zScore($key, $member);
    }

    public function zRevRank($key, $member) {
        return self::init()->zRevRank($key, $member);
    }
    // -----------------------SortedSet End-----------------------


    // -----------------------Set Start-----------------------
    public function sAdd($key, $value) {
        return self::init()->sAdd($key, $value);
    }

    public function sCard($key) {
        return self::init()->sCard($key);
    }
    // -----------------------Set End-----------------------*/


}