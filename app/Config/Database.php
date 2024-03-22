<?php

namespace Rrim\PhpUserManagement\Config;

class Database
{
    private static ?\PDO $pdo = null;

    public static function getConnection(string $env = "test") : \PDO
    {

        if (self::$pdo==null){
            require_once __DIR__ . "/../../config/Database.php";

            $config = getDatabaseConfig();
            self::$pdo = new \PDO(
                $config['database'][$env]['url'],
                $config['database'][$env]['username'],
                $config['database'][$env]['password']
        );
        }
        return self::$pdo;
    }

    public static function beginTransaction()
    {
        self::$pdo->beginTransaction();

    }

    public static function commitTransaction()
    {
        self::$pdo->commit();
    }

    public static function rollbackTransaction()
    {
        self::$pdo->rollBack();

    }

}