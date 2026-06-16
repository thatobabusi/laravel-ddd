<?php

namespace Laravel\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class MakeMapperCommand extends GeneratorCommand
{
    protected $name = 'ddd:mapper';
    protected $description = 'Create a new Domain-Infrastructure mapper';
    protected $type = 'Mapper';

    protected function getStub()
    {
        return __DIR__.'/../../stubs/ddd/mapper.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Application\\Mappers';
    }
}
