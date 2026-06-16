<?php

namespace Laravel\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class DomainResponseMakeCommand extends GeneratorCommand
{
    protected $name = 'ddd:response';
    protected $description = 'Create a new domain response interface and implementation';
    protected $type = 'Response';

    protected function getStub()
    {
        return str_contains($this->getNameInput(), 'I')
            ? __DIR__.'/../../stubs/ddd/response-interface.stub'
            : __DIR__.'/../../stubs/ddd/response.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Presentation\\Responses';
    }

    public function handle()
    {
        $name = $this->getNameInput();

        if (!str_ends_with($name, 'Response')) {
            $name .= 'Response';
        }

        // Create the interface
        $this->call('ddd:response', ['name' => 'I' . $name]);

        // Create the implementation
        $this->call('ddd:response', ['name' => $name]);

        return 0;
    }
}
