<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ConsoleMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainConsoleMakeCommand extends ConsoleMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:command';
}
