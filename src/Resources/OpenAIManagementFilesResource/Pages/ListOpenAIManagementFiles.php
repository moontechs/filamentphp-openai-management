<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource;

class ListOpenAIManagementFiles extends ListRecords
{
    protected static string $resource = OpenAIManagementFilesResource::class;

    protected static ?string $title = 'OpenAI Files';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
