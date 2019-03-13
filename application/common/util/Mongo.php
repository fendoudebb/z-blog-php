<?php

namespace app\common\util;


use MongoDB\Driver\Command;
use MongoDB\Driver\Exception\Exception;
use MongoDB\Driver\Manager;

class Mongo {

    private static $db = 'z-blog';

    public static function cmd($cmd) {
        $manager = new Manager("mongodb://127.0.0.1:27017");
        $command = new Command($cmd);
        try {
            return $manager->executeCommand(Mongo::$db, $command)->toArray();
        } catch (Exception $e) {
            return [];
        }
    }

}