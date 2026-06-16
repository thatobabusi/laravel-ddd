<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Laravel\LaravelDDD\Commands\Concerns\InteractsWithStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;
use Laravel\LaravelDDD\Support\DomainResolver;

abstract class DomainGeneratorCommand extends GeneratorCommand
{
    use InteractsWithStubs,
        ResolvesDomainFromInput;

    protected function getRelativeDomainNamespace(): string
    {
        return DomainResolver::getRelativeObjectNamespace($this->blueprint->type);
    }

    protected function getNameInput()
    {
        return Str::studly($this->argument('name'));
    }
}
