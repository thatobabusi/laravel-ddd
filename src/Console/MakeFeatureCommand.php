<?php

namespace Tey\LaravelDDD\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

/**
 * MakeFeatureCommand
 *
 * Inspired by imran-ahmed-optilius/laravel-ddd-maker
 * Orchestrates multiple generators to create a complete feature (UseCase, Service, Repository, etc.)
 * with intelligent prompting about complexity.
 */
class MakeFeatureCommand extends Command
{
    protected $signature = 'ddd:make:feature
                          {prefix : Feature prefix (e.g. ForHomePageTeacherGet)}
                          {--folder= : Feature folder name (e.g. HomePage)}
                          {--no-interaction : Skip interactive prompts}';

    protected $description = 'Scaffold a complete DDD feature (UseCase, Service, Repository, DTO, Response) with interactive guidance';

    protected Filesystem $files;

    public function handle(Filesystem $files)
    {
        $this->files = $files;

        $this->showHeader();

        $prefix = $this->argument('prefix');
        $folder = $this->option('folder');

        if (!$folder) {
            $folder = $this->ask('Enter the feature folder name (e.g. HomePage or Users/Profile)');
        }

        // Ask complexity questions
        $answers = $this->askComplexityQuestions();

        // Show what will be generated
        $this->showGenerationPlan($prefix, $folder, $answers);

        // Generate files
        if ($this->confirm('Generate all files now?', true)) {
            $this->generateFeature($prefix, $folder, $answers);
            $this->showBindingCode($prefix, $folder, $answers);
            $this->showRouteCode($prefix, $folder);
            $this->showNextSteps();
            $this->info('✓ Done! All files generated successfully.');
        } else {
            $this->info('Cancelled.');
        }
    }

    protected function showHeader(): void
    {
        $this->line('');
        $this->line('╔═══════════════════════════════════════════════════╗');
        $this->line('║                @thatobabusidev                    ║');
        $this->line('║               Laravel DDD Maker                   ║');
        $this->line('║  Clean Architecture + Domain-Driven Design        ║');
        $this->line('╚═══════════════════════════════════════════════════╝');
        $this->line('');
    }

    protected function askComplexityQuestions(): array
    {
        $this->section('Complexity & Design Questions');

        return [
            'with_value_objects' => $this->confirm('Will this feature require Value Objects (VO)?', false),
            'repository_input_dto' => $this->confirm('Will the Repository need an Input DTO?', false),
            'service_input_dto' => $this->confirm('Will the Service need an Input DTO?', false),
            'multiple_responses' => $this->confirm('Will the Response have multiple names?', false),
            'multiple_outputs' => $this->confirm('Will the Output (DTO) have multiple names?', false),
            'multiple_repositories' => $this->confirm('Will there be multiple Repositories?', false),
            'with_entity' => $this->confirm('Is there a need for a domain Entity class?', false),
            'with_eloquent_model' => $this->confirm('Should an Eloquent Model be generated?', false),
            'with_request' => $this->confirm('Will this feature need a Request (Form Request) class?', true),
        ];
    }

    protected function showGenerationPlan(string $prefix, string $folder, array $answers): void
    {
        $this->section('Files to be generated');

        if ($answers['with_request']) {
            $this->line("  <fg=cyan>Request:</>");
            $this->line("    • app/Http/Requests/Api/V1/{$folder}/{$prefix}Request.php");
        }

        $this->line("  <fg=cyan>Action (Invokable Controller):</>");
        $this->line("    • app/Http/Controllers/Api/V1/{$folder}/{$prefix}Action.php");

        $this->line("  <fg=cyan>UseCase:</>");
        $this->line("    • app/UseCases/{$folder}/I{$prefix}UseCase.php");
        $this->line("    • app/UseCases/{$folder}/{$prefix}UseCase.php");

        $this->line("  <fg=cyan>Domain Service:</>");
        $this->line("    • app/Domain/{$folder}/Services/I{$prefix}Service.php");
        $this->line("    • app/Infra/{$folder}/Services/{$prefix}Service.php");

        if ($answers['repository_input_dto']) {
            $this->line("  <fg=cyan>Repository Input DTO:</>");
            $this->line("    • app/Domain/{$folder}/Repositories/Input/{$prefix}Input.php");
        }

        $this->line("  <fg=cyan>Repository:</>");
        $this->line("    • app/Domain/{$folder}/Repositories/I{$prefix}Repository.php");
        $this->line("    • app/Infra/{$folder}/Repositories/{$prefix}Repository.php");

        $this->line("  <fg=cyan>Output DTO:</>");
        $this->line("    • app/Domain/{$folder}/Services/Output/{$prefix}Output.php");

        $this->line("  <fg=cyan>Response:</>");
        $this->line("    • app/Http/Responses/Api/V1/{$folder}/I{$prefix}Response.php");
        $this->line("    • app/Http/Responses/Api/V1/{$folder}/{$prefix}Response.php");

        if ($answers['with_value_objects']) {
            $this->line("  <fg=cyan>Value Objects:</>");
            $this->line("    • app/Domain/{$folder}/Vo/ (placeholder)");
        }

        if ($answers['with_entity']) {
            $this->line("  <fg=cyan>Entity:</>");
            $this->line("    • app/Models/Entities/{$prefix}Entity.php");
        }

        if ($answers['with_eloquent_model']) {
            $this->line("  <fg=cyan>Eloquent Model:</>");
            $this->line("    • app/Models/{$prefix}Model.php");
        }

        $this->line('');
    }

    protected function generateFeature(string $prefix, string $folder, array $answers): void
    {
        $this->section('Generating');

        // Note: Using forward-slash syntax to ensure generators respect sub-folders
        if ($answers['with_request']) {
            $this->call('ddd:request', ['name' => "{$folder}/{$prefix}Request"]);
        }

        $this->call('ddd:controller', ['name' => "{$folder}/{$prefix}Action", '--invokable' => true]);
        $this->call('ddd:use-case', ['name' => "{$folder}/{$prefix}"]);
        
        if ($answers['service_input_dto']) {
            $this->call('ddd:dto', ['name' => "{$folder}/Input/{$prefix}ServiceInput"]);
        }
        $this->call('ddd:service', ['name' => "{$folder}/{$prefix}Service"]);

        if ($answers['repository_input_dto']) {
            $this->call('ddd:dto', ['name' => "{$folder}/Repositories/Input/{$prefix}RepositoryInput"]);
        }
        $this->call('ddd:repository', ['name' => "{$folder}/{$prefix}Repository"]);

        $this->call('ddd:dto', ['name' => "{$folder}/Services/Output/{$prefix}Output"]);
        $this->call('ddd:response', ['name' => "{$folder}/{$prefix}"]);

        if ($answers['with_value_objects']) {
            $this->info("Creating Vo folder in Domain/{$folder}/Vo...");
        }

        if ($answers['with_entity']) {
            $this->call('ddd:class', ['name' => "{$folder}/Entities/{$prefix}Entity"]);
        }

        if ($answers['with_eloquent_model']) {
            $this->call('ddd:eloquent-model', ['name' => "{$folder}/{$prefix}Model"]);
        }

        $this->info('✔ All files created successfully');
    }

    protected function section(string $title): void
    {
        $this->line('');
        $this->line("  <fg=white;bg=blue;options=bold> {$title} </>");
        $this->line('');
    }

    protected function showBindingCode(string $prefix, string $folder, array $answers): void
    {
        $this->section('Add to AppServiceProvider::register()');

        $code = <<<PHP
        // {$folder} - {$prefix}
        \$this->app->bind(
            App\\UseCases\\{$folder}\\I{$prefix}UseCase::class,
            App\\UseCases\\{$folder}\\{$prefix}UseCase::class
        );
        \$this->app->bind(
            App\\Domain\\{$folder}\\Services\\I{$prefix}Service::class,
            App\\Infra\\{$folder}\\Services\\{$prefix}Service::class
        );
        \$this->app->bind(
            App\\Domain\\{$folder}\\Repositories\\I{$prefix}Repository::class,
            App\\Infra\\{$folder}\\Repositories\\{$prefix}Repository::class
        );
        \$this->app->bind(
            App\\Http\\Responses\\Api\\V1\\{$folder}\\I{$prefix}Response::class,
            App\\Http\\Responses\\Api\\V1\\{$folder}\\{$prefix}Response::class
        );
        PHP;

        $this->line("<fg=yellow>{$code}</>");
    }

    protected function showRouteCode(string $prefix, string $folder): void
    {
        $this->section('Add to routes/api.php');

        $routeName = str($prefix)->snake()->toString();
        $controllerPath = "App\\Http\\Controllers\\Api\\V1\\{$folder}\\{$prefix}Action";

        $code = "Route::get('/{$routeName}', \\{$controllerPath}::class);";

        $this->line("<fg=yellow>{$code}</>");
    }

    protected function showNextSteps(): void
    {
        $this->section('Next Steps');

        $this->line('1. Add binding code to AppServiceProvider::register()');
        $this->line('2. Add route code to routes/api.php');
        $this->line('3. Fill in TODO comments in generated files:');
        $this->line('   - Request: Add validation rules()');
        $this->line('   - Service: Implement business logic');
        $this->line('   - Repository: Implement Eloquent queries');
        $this->line('   - Output DTO: Define properties and getters');
        $this->line('   - Response: Map DTO to API response format');
    }
}
