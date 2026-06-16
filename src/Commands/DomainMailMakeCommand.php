<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\MailMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainMailMakeCommand extends MailMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:mail';
}
