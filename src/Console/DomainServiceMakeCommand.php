<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class DomainServiceMakeCommand extends GeneratorCommand
{
    protected $name = 'ddd:service';
    protected $description = 'Create a new domain service interface and implementation';
    protected $type = 'Service';

    protected function getStub()
    {
        return str_contains($this->getNameInput(), 'I') 
            ? __DIR__.'/../../stubs/ddd/service-interface.stub'
            : __DIR__.'/../../stubs/ddd/service.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Domain\\Services';
    }

    public function handle()
    {
        $name = $this->getNameInput();
        
        if (!str_ends_with($name, 'Service')) {
            $name .= 'Service';
        }

        // Create the interface
        $this->call('ddd:service', ['name' => 'I' . $name]);

        // Create the implementation
        $this->call('ddd:service', ['name' => $name]);

        return 0;
    }
}
