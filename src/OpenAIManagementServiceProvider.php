<?php

namespace Moontechs\OpenAIManagement;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Moontechs\OpenAIManagement\Commands\OpenAIManagementBatchesUpdateCommand;
use Moontechs\OpenAIManagement\Commands\OpenAIManagementFilesUpdateCommand;
use Moontechs\OpenAIManagement\Commands\OpenAIManagementProcessedFilesDownloadCommand;
use Moontechs\OpenAIManagement\Testing\TestsOpenaiBatchesManagement;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class OpenAIManagementServiceProvider extends PackageServiceProvider
{
    public static string $name = 'openai-management';

    public static string $viewNamespace = 'openai-management';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('moontechs/openai-management');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__.'/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/openai-management/{$file->getFilename()}"),
                ], 'openai-management-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsOpenaiBatchesManagement);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'moontechs/openai-management';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('openai-batches-management', __DIR__ . '/../resources/dist/components/openai-batches-management.js'),
            // Css::make('openai-batches-management-styles', __DIR__ . '/../resources/dist/openai-batches-management.css'),
            // Js::make('openai-batches-management-scripts', __DIR__ . '/../resources/dist/openai-batches-management.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            OpenAIManagementFilesUpdateCommand::class,
            OpenAIManagementBatchesUpdateCommand::class,
            OpenAIManagementProcessedFilesDownloadCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_openai_management_projects_table',
            'create_openai_management_files_table',
            'create_openai_management_batches_table',
        ];
    }
}
