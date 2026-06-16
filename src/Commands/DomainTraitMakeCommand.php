<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\TraitMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainTraitMakeCommand extends TraitMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:trait';
}
