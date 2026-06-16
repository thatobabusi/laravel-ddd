<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class MakeCommandQueryCommand extends GeneratorCommand
{
    protected $name = 'ddd:command-query';
    protected $description = 'Create a Command or Query handler (CQRS pattern)';
    protected $type = 'Command/Query';

    protected $signature = 'ddd:command-query {name : The name of the command or query}
                           {--query : Create a Query instead of a Command}';

    protected function getStub()
    {
        return $this->option('query')
            ? __DIR__.'/../../stubs/ddd/query.stub'
            : __DIR__.'/../../stubs/ddd/command.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        $type = $this->option('query') ? 'Queries' : 'Commands';
        return $rootNamespace."\\Application\\{$type}";
    }
}
