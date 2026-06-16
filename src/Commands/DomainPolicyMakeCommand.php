<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\PolicyMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainPolicyMakeCommand extends PolicyMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:policy';
}
