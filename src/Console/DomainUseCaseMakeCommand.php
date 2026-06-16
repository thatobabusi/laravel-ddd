<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class DomainUseCaseMakeCommand extends GeneratorCommand
{
    protected $name = 'ddd:use-case';
    protected $description = 'Create a new domain use case interface and implementation';
    protected $type = 'Use Case';

    protected function getStub()
    {
        return str_contains($this->getNameInput(), 'I') 
            ? __DIR__.'/../../stubs/ddd/use-case.stub'
            : __DIR__.'/../../stubs/ddd/use-case-impl.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Application\\UseCases';
    }

    public function handle()
    {
        $name = $this->getNameInput();
        
        if (!str_ends_with($name, 'UseCase')) {
            $name .= 'UseCase';
        }

        // Create the interface
        $this->call('ddd:use-case', ['name' => 'I' . $name]);

        // Create the implementation
        $this->call('ddd:use-case', ['name' => $name]);

        return 0;
    }
}
