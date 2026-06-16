<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\EventMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainEventMakeCommand extends EventMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:event';
}
