<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class MakePolicyCommand extends GeneratorCommand
{
    protected $name = 'ddd:policy';
    protected $description = 'Create a new domain authorization policy';
    protected $type = 'Policy';

    protected function getStub()
    {
        return __DIR__.'/../../stubs/ddd/policy.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Domain\\Policies';
    }
}
