<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class StartPythonApi extends Command
{
    protected $signature = 'python-api:start {--port=5000}';
    protected $description = 'Start the Python API service';

    public function handle()
    {
        $port = $this->option('port');

        $this->info('Starting Python API service on port ' . $port);

        // Check if Python and required packages are installed
        $this->checkPythonRequirements();

        // Start the Python API service
        $process = new Process([
            'python',
            base_path('python_api/api_wrapper.py'),
            $port
        ]);

        $process->setWorkingDirectory(base_path());
        $process->setTimeout(null);
        $process->start();

        $pid = $process->getPid();
        $this->info("Python API service started with PID: $pid");

        // Store the PID in a file for later use
        file_put_contents(storage_path('app/python_api.pid'), $pid);

        // Keep running and display output
        foreach ($process as $type => $data) {
            if ($type === $process::OUT) {
                $this->info($data);
            } else {
                $this->error($data);
            }
        }
    }

    protected function checkPythonRequirements()
    {
        // Check if Python is installed
        $pythonProcess = new Process(['python', '--version']);
        $pythonProcess->run();

        if (!$pythonProcess->isSuccessful()) {
            $this->error('Python is not installed. Please install Python 3.7 or higher.');
            exit(1);
        }

        // Check if required packages are installed
        $requirementsFile = base_path('python_api/requirements.txt');

        if (file_exists($requirementsFile)) {
            $this->info('Installing Python dependencies...');

            $pipProcess = new Process([
                'pip',
                'install',
                '-r',
                $requirementsFile
            ]);

            $pipProcess->run(function ($type, $buffer) {
                if ($type === Process::ERR) {
                    $this->error($buffer);
                } else {
                    $this->info($buffer);
                }
            });

            if (!$pipProcess->isSuccessful()) {
                $this->error('Failed to install Python dependencies.');
                exit(1);
            }
        }
    }
}
