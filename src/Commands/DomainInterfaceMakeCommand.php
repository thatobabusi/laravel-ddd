<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\InterfaceMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainInterfaceMakeCommand extends InterfaceMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:interface';
}
