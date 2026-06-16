<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class DomainActionMakeCommand extends GeneratorCommand
{
    protected $name = 'ddd:action';
    protected $description = 'Create a new domain action';
    protected $type = 'Action';

    protected function getStub()
    {
        return __DIR__.'/../../stubs/ddd/action.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Domain\\Actions';
    }
}
