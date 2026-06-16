<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\JobMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainJobMakeCommand extends JobMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:job';
}
