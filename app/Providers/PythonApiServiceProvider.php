<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class PythonApiServiceProvider extends ServiceProvider
{
    protected $pythonProcess = null;

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('python-api', function ($app) {
            return $this;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only start Python API in web environment, not during artisan commands
        if (!$this->app->runningInConsole() && php_sapi_name() !== 'cli') {
            $this->startPythonApi();

            // Register shutdown function
            register_shutdown_function(function () {
                $this->stopPythonApi();
            });
        }
    }

    /**
     * Start the Python API service
     */
    protected function startPythonApi(): void
    {
        // Check if API is already running
        $pidFile = storage_path('app/python_api.pid');

        if (file_exists($pidFile)) {
            $pid = file_get_contents($pidFile);

            // On Windows
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $checkProcess = new Process(['tasklist', '/FI', "PID eq $pid"]);
                $checkProcess->run();

                if (strpos($checkProcess->getOutput(), $pid) !== false) {
                    // Process is running
                    return;
                }
            } else {
                // On Linux/Unix/MacOS
                $checkProcess = new Process(['ps', '-p', $pid, '-o', 'pid=']);
                $checkProcess->run();

                if ($checkProcess->isSuccessful()) {
                    // Process is running
                    return;
                }
            }

            // Process is not running, remove stale PID file
            unlink($pidFile);
        }

        // Start the Python API
        try {
            Log::info('Starting Python API service');

            $pythonPath = config('services.python_api.python_path', 'python');
            $apiPort = config('services.python_api.port', 5000);

            $this->pythonProcess = new Process([
                $pythonPath,
                base_path('python_api/api_wrapper.py'),
                (string)$apiPort
            ]);

            $this->pythonProcess->setWorkingDirectory(base_path());
            $this->pythonProcess->start();

            // Get and save PID
            $pid = $this->pythonProcess->getPid();
            file_put_contents($pidFile, $pid);

            Log::info("Python API service started with PID: $pid");
        } catch (\Exception $e) {
            Log::error('Failed to start Python API: ' . $e->getMessage());
        }
    }

    /**
     * Stop the Python API service
     */
    protected function stopPythonApi(): void
    {
        // Stop the process if we started it
        if ($this->pythonProcess && $this->pythonProcess->isRunning()) {
            Log::info('Stopping Python API service');
            $this->pythonProcess->stop();
        }
    }
}
