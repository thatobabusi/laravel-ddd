<?php

namespace Laravel\LaravelDDD\Commands;

use Illuminate\Foundation\Console\NotificationMakeCommand;
use Laravel\LaravelDDD\Commands\Concerns\HasDomainStubs;
use Laravel\LaravelDDD\Commands\Concerns\ResolvesDomainFromInput;

class DomainNotificationMakeCommand extends NotificationMakeCommand
{
    use HasDomainStubs,
        ResolvesDomainFromInput;

    protected $name = 'ddd:notification';
}
