<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\MultiDatabase;
use App\Service\MultiMigrationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class multiMigrationCMD extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'multi:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Disconnect from multi database if connected
        MultiMigrationService::disconnectFromMulti();

        // Get all active migrations
        $migrations = MultiDatabase::where('status', 1)->get();

        // Perform migration for each active database
        $migrations->each(function ($migration) {
            $this->performMigration($migration);
        });

        // Disconnect from multi database again
        MultiMigrationService::disconnectFromMulti();

        $this->info('Multi migration completed successfully.');

        return 0;
    }

    /**
     * Perform migration for a specific database.
     *
     * @return void
     */
    protected function performMigration(MultiDatabase $migration)
    {
        MultiMigrationService::switchToMulti($migration);

        $this->info('Starting migration for '.$migration->host);

        // Run migration command
        Artisan::call('migrate', [
            '--path' => 'database/migrations/Multi_Migration',
            '--database' => 'mysql2',
        ]);

        $this->info('Migration completed successfully for '.$migration->host);
    }
}
