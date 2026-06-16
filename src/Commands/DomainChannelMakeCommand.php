<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\ChannelMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainChannelMakeCommand extends ChannelMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:channel';
}
