<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeDomainCommand extends Command
{
    protected $signature = 'ddd:make:domain {context : The name of the bounded-context}';
    protected $description = 'Scaffold a new bounded-context with Domain, Application, Presentation, and Infrastructure layers';

    public function handle(Filesystem $filesystem)
    {
        $context = $this->argument('context');
        $path = base_path("src/{$context}");

        $layers = ['Domain', 'Application', 'Presentation', 'Infrastructure'];

        foreach ($layers as $layer) {
            $layerPath = "{$path}/{$layer}";
            if (!$filesystem->isDirectory($layerPath)) {
                $filesystem->makeDirectory($layerPath, 0755, true);
                $this->info("Created layer: {$layer}");
            }
        }

        $this->info("Bounded-context [{$context}] scaffolded successfully.");
    }
}
