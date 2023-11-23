<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class CreateDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:db {databaseName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a database new ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $databaseName = $this->argument('databaseName');
        if (env('APP_ENV') == 'local') {
            $connection = config('database.default');

            $driver = config("database.connections.{$connection}.driver");

            if ($driver === 'mysql') {
                $query = "CREATE DATABASE IF NOT EXISTS $databaseName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci";
                DB::statement($query);

                $this->info("Database '$databaseName' created successfully.");
            } else {
                $this->error("Unsupported database driver: $driver");
            }
        } else {
            $cmd = "uapi Mysql create_database name=$databaseName";
            $process = Process::fromShellCommandline($cmd);
            $process->run();
            $cmd = "uapi Mysql set_privileges_on_database user=".config('database.connections.mysql.username') ." database=$databaseName privileges=ALL";
            $process = Process::fromShellCommandline($cmd);
            $process->run();

            if ($process->isSuccessful()) {
                return $this->info("Database '$databaseName' and use ".config('database.connections.mysql.username')." created successfully.");
            } else {
                return $this->info("Database '$databaseName' created error.".$process->getErrorOutput());
            }
        }
    }
}