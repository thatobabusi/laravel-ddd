<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class MakeDomainServiceProviderCommand extends GeneratorCommand
{
    protected $name = 'ddd:provider';
    protected $description = 'Create a domain-specific ServiceProvider for binding abstractions';
    protected $type = 'Provider';

    protected function getStub()
    {
        return __DIR__.'/../../stubs/ddd/domain-provider.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Infrastructure\\Providers';
    }
}
