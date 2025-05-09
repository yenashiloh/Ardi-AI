<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class StopPythonApi extends Command
{
    protected $signature = 'python-api:stop';
    protected $description = 'Stop the Python API service';

    public function handle()
    {
        $pidFile = storage_path('app/python_api.pid');

        if (!file_exists($pidFile)) {
            $this->error('Python API service is not running or PID file not found');
            return;
        }

        $pid = file_get_contents($pidFile);

        if (!$pid) {
            $this->error('Invalid PID in file');
            unlink($pidFile);
            return;
        }

        $this->info("Stopping Python API service with PID: $pid");

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $process = new Process(['taskkill', '/F', '/PID', $pid]);
        } else {
            // Linux/Unix/MacOS
            $process = new Process(['kill', '-9', $pid]);
        }

        $process->run();

        if ($process->isSuccessful()) {
            $this->info('Python API service stopped successfully');
        } else {
            $this->error('Failed to stop Python API service: ' . $process->getErrorOutput());
        }

        unlink($pidFile);
    }
}
