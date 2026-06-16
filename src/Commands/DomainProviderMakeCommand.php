<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ProviderMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainProviderMakeCommand extends ProviderMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:provider';
}
