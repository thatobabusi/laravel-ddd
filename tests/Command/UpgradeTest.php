<?php

use Illuminate\Support\Facades\File;

it('skips upgrade if config file was not published', function () {
    $path = config_path('ddd.php');

    if (file_exists($path)) {
        unlink($path);
    }

    expect(file_exists($path))->toBeFalse();

    $this->artisan('ddd:upgrade')
        ->expectsOutputToContain('Config file was not published. Nothing to upgrade!')
        ->execute();

    expect(file_exists($path))->toBeFalse();
});

describe('v3 namespace migration', function () {
    beforeEach(function () {
        // Publish a minimal config so the command doesn't bail early
        $configFilePath = config_path('ddd.php');
        if (! file_exists($configFilePath)) {
            File::copy(__DIR__.'/../../config/ddd.php', $configFilePath);
        }
    });

    afterEach(function () {
        $path = config_path('ddd.php');
        if (file_exists($path)) {
            unlink($path);
        }
    });

    it('rewrites old namespace references in config/ddd.php', function () {
        $configPath = config_path('ddd.php');
        $original = file_get_contents($configPath);

        // Inject an old namespace reference into the config
        $modified = str_replace(
            "'base_model' => null",
            "'base_model' => 'Lunarstorm\\LaravelDDD\\Models\\DomainModel'",
            $original,
        );
        file_put_contents($configPath, $modified);

        expect(file_get_contents($configPath))->toContain('Lunarstorm\LaravelDDD');

        $this->artisan('ddd:upgrade')->execute();

        $config = require $configPath;

        expect($config['base_model'])
            ->not->toContain('Lunarstorm')
            ->toContain('Tey\LaravelDDD');
    });

    it('reports no files when app has no old namespace references', function () {
        $this->artisan('ddd:upgrade')
            ->expectsOutputToContain('No application files require namespace updates')
            ->execute();
    });

    it('replaces old namespace in app php files when confirmed', function () {
        $tmpFile = app_path('SomeService.php');
        File::ensureDirectoryExists(app_path());
        file_put_contents($tmpFile, "<?php\nuse Lunarstorm\\LaravelDDD\\Facades\\DDD;\n");

        $this->artisan('ddd:upgrade')
            ->expectsConfirmation(
                'Replace all occurrences of [Lunarstorm\LaravelDDD] with [Tey\LaravelDDD]?',
                'yes',
            )
            ->execute();

        expect(file_get_contents($tmpFile))
            ->not->toContain('Lunarstorm\\LaravelDDD')
            ->toContain('Tey\\LaravelDDD');

        unlink($tmpFile);
    });

    it('skips replacing app files when not confirmed', function () {
        $tmpFile = app_path('SomeService.php');
        File::ensureDirectoryExists(app_path());
        file_put_contents($tmpFile, "<?php\nuse Lunarstorm\\LaravelDDD\\Facades\\DDD;\n");

        $this->artisan('ddd:upgrade')
            ->expectsConfirmation(
                'Replace all occurrences of [Lunarstorm\LaravelDDD] with [Tey\LaravelDDD]?',
                'no',
            )
            ->expectsOutputToContain('You will need to update these files manually.')
            ->execute();

        expect(file_get_contents($tmpFile))->toContain('Lunarstorm\\LaravelDDD');

        unlink($tmpFile);
    });
});
