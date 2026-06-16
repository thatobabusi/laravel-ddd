<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\GeneratorCommand;

class MakeRepositoryCommand extends GeneratorCommand
{
    protected $name = 'ddd:repository';
    protected $description = 'Create a new Repository implementation and interface';
    protected $type = 'Repository';

    protected function getStub()
    {
        return __DIR__.'/../../stubs/ddd/repository.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\\Application\\Repositories';
    }

    public function handle()
    {
        parent::handle();

        // Also create the interface
        $name = $this->qualifyClass($this->getNameInput());
        $interface = str_replace('Repositories', 'Contracts', $name).'Interface';
        
        $this->call('make:class', [
            'name' => str_replace('\\', '/', $interface),
        ]);

        $this->info("Repository interface created at: {$interface}");
    }
}
