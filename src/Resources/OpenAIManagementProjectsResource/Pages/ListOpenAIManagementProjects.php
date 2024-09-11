<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource;

class ListOpenAIManagementProjects extends ListRecords
{
    protected static string $resource = OpenAIManagementProjectsResource::class;

    protected static ?string $title = 'OpenAI Projects';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
