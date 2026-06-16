<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;
use Illuminate\Support\Str;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;
use Laravel\LaravelDDD\Support\DomainResolver;

class DomainRequestMakeCommand extends RequestMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:request';

    protected function rootNamespace()
    {
        return Str::finish(DomainResolver::resolveRootNamespace($this->blueprint->type), '\\');
    }
}
