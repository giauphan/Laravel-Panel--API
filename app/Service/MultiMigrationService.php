<?php

namespace App\Service;

use App\Models\MultiDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class MultiMigrationService
{
    private static $multi;

    public static function switchToMulti(MultiDatabase $migration)
    {
        self::configureDatabaseConnection('mysql2', $migration);
        self::$multi = $migration;
    }

    public static function switchToDefault()
    {
        self::configureDatabaseConnection('mysql');
    }

    public static function getMulti()
    {
        return self::$multi;
    }

    public static function disconnectFromMulti()
    {
        DB::disconnect('mysql2');
        self::switchToDefault();
    }

    private static function configureDatabaseConnection($connection, ?MultiDatabase $migration = null)
    {
        $configName = $connection === 'mysql' ? 'mysql' : 'mysql2';

        DB::purge($configName);

        if ($migration) {
            Config::set("database.connections.{$configName}.database", $migration->database);
            Config::set("database.connections.{$configName}.port", $migration->port);
            Config::set("database.connections.{$configName}.host", $migration->host);
            Config::set("database.connections.{$configName}.username", $migration->username);
            Config::set("database.connections.{$configName}.password", $migration->password);
        }

        DB::connection($configName)->reconnect();
        DB::setDefaultConnection($configName);
    }
}
