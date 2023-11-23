<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SettingServerStorage extends Settings
{
    public string $server_name;

    public string $url;

    public string $database_name;

    public string $limit_database_mb;

    public static function group(): string
    {
        return 'server';
    }
}
