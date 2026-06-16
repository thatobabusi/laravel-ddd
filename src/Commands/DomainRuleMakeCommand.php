<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\RuleMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainRuleMakeCommand extends RuleMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:rule';
}
