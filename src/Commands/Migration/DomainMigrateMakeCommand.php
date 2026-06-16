<?php

namespace Laravel\LaravelDDD\Commands\Migration;

use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;
use Laravel\LaravelDDD\Support\Path;

class DomainMigrateMakeCommand extends BaseMigrateMakeCommand
{
    use ResolvesDomainFromInput;

    protected $name = 'ddd:migration';

    /**
     * Get the path to the migration directory.
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        if ($this->blueprint) {
            return $this->laravel->basePath($this->blueprint->getMigrationPath());
        }

        return $this->laravel->databasePath().DIRECTORY_SEPARATOR.'migrations';
    }
}
