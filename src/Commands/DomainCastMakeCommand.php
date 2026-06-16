<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\CastMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainCastMakeCommand extends CastMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:cast';
}
