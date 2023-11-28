<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SettingServerStorage extends Settings
{
    public string $server_name;

    public string $url;

    public string $database_name = 'bcdn';

    public int $limit_database_mb = 100;

    public static function group(): string
    {
        return 'Server';
    }
}
