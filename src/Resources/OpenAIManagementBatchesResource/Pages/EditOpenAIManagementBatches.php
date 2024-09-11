<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource;

class EditOpenAIManagementBatches extends EditRecord
{
    protected static string $resource = OpenAIManagementBatchesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
