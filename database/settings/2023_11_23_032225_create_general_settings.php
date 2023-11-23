<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('Server.server_name', 'storage');
        $this->migrator->add('Server.url', '');
        $this->migrator->add('Server.database_name', 'bcdn');
        $this->migrator->add('Server.limit_database_mb', 1000);
    }
};
