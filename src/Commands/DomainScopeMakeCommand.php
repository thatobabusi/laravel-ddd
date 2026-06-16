<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ScopeMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainScopeMakeCommand extends ScopeMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:scope';
}
