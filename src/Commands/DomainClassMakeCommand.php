<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ClassMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainClassMakeCommand extends ClassMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:class';
}
