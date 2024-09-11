<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource;

class ListOpenAIManagementBatches extends ListRecords
{
    protected static string $resource = OpenAIManagementBatchesResource::class;

    protected static ?string $title = 'OpenAI Batches';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
