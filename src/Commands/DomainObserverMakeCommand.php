<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ObserverMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainObserverMakeCommand extends ObserverMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:observer';
}
