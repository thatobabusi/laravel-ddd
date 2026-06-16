<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Routing\Console\MiddlewareMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainMiddlewareMakeCommand extends MiddlewareMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:middleware';
}
