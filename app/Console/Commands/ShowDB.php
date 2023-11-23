<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ShowDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'show:db';

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
        $databases = DB::select('SHOW DATABASES');

        if (empty($databases)) {
            $this->info('No databases found.');
        } else {
            $this->info('List of databases:');

            foreach ($databases as $database) {
                $this->line('- '.$database->Database);
            }
        }
    }
}
