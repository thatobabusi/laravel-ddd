<?php

namespace Laravel\LaravelDDD\Commands\Concerns;

use Laravel\LaravelDDD\Support\GeneratorBlueprint;

trait HasGeneratorBlueprint
{
    protected ?GeneratorBlueprint $blueprint = null;
}
