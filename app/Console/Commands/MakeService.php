<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeService extends Command
{
    // The name and signature of the console command.
    protected $signature = 'make:service {name : The name of the service class}';

    // The console command description.
    protected $description = 'Create a new service class';

    // Execute the console command.
    public function handle()
    {
        // Retrieve the service name from the argument
        $name = $this->argument('name');

        // Determine the file path for the new service (e.g., app/Services/ArticleService.php)
        $path = app_path('Http/Services/' . $name . '.php');

        // Create the directory if it does not exist
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        // Check if the service file already exists
        if (file_exists($path)) {
            $this->error("Service {$name} already exists!");
            return 1;
        }

        // Get the stub file contents
        $stub = file_get_contents($this->getStub());

        // Replace the stub placeholders with actual values
        $stub = str_replace('{{ namespace }}', 'App\\Http\\Services', $stub);
        $stub = str_replace('{{ class }}', $name, $stub);

        // Write the new service file to the determined path
        file_put_contents($path, $stub);

        $this->info("Service {$name} created successfully at {$path}");
        return 0;
    }

    // Helper method to locate the stub file
    protected function getStub()
    {
        // Adjust the path if your stubs are in a different location
        return base_path('stubs/service.stub');
    }
}
