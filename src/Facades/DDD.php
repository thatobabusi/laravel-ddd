<?php

namespace Laravel\LaravelDDD\Facades;

use Illuminate\Support\Facades\Facade;
use Laravel\LaravelDDD\DomainManager;

/**
 * @see DomainManager
 *
 * @method static void filterAutoloadPathsUsing(callable $filter)
 * @method static ?callable getAutoloadFilter()
 * @method static void resolveObjectSchemaUsing(callable $resolver)
 * @method static string packagePath(string $path = '')
 * @method static \Laravel\LaravelDDD\Support\AutoloadManager autoloader()
 * @method static \Laravel\LaravelDDD\ConfigManager config()
 * @method static \Laravel\LaravelDDD\StubManager stubs()
 * @method static \Laravel\LaravelDDD\ComposerManager composer()
 */
class DDD extends Facade
{
    protected static function getFacadeAccessor()
    {
        return DomainManager::class;
    }
}
