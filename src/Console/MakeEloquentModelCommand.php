<?php

namespace Laravel\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class MakeEloquentModelCommand extends GeneratorCommand
{
    protected $name = 'ddd:eloquent-model';
    protected $description = 'Create a new Eloquent model in the Infrastructure layer';
    protected $type = 'Eloquent Model';

    protected function getStub()
    {
        return __DIR__.'/../../stubs/ddd/eloquent-model.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Infrastructure\\Models';
    }
}
