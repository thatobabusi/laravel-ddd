<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainResourceMakeCommand extends ResourceMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:resource';
}
