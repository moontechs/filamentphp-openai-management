<?php

namespace Moontechs\OpenAIManagement;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource;

class OpenAIManagementPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filamentphp-openai-management';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                OpenAIManagementProjectsResource::class,
                OpenAIManagementFilesResource::class,
            ]);
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
