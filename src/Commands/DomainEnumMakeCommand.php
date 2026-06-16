<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\EnumMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainEnumMakeCommand extends EnumMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:enum';
}
