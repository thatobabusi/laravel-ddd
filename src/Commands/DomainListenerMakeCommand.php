<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ListenerMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainListenerMakeCommand extends ListenerMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:listener';
}
