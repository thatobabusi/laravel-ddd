<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Database\Console\Seeds\SeederMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainSeederMakeCommand extends SeederMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:seeder';
}
