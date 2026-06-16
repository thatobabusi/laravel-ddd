<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ExceptionMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainExceptionMakeCommand extends ExceptionMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:exception';
}
