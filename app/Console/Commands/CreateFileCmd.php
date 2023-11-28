<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class CreateFileCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:file {filename} {fileType} {fileContent}';

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

        $filename = $this->argument('filename');
        $fileType = $this->argument('fileType');
        $file = $this->argument('fileContent');


        $path_file = public_path() . "/file.pdf";

        file_put_contents($path_file, base64_decode($file));

        $cmd = "/usr/bin/php /home/kingking/ApiWebDriver/artisan file:upload   $filename $fileType $path_file";

        $process = Process::fromShellCommandline($cmd);
        $process->run();

        $output = $process->getOutput();

        $outputLines = explode("\n", trim($output));  // Use "\n" as the delimiter
        dump($outputLines, $outputLines[0] == "Duplicate record");
    }
}
