<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource;

class CreateOpenAIManagementBatches extends CreateRecord
{
    protected static string $resource = OpenAIManagementBatchesResource::class;

    public function form(Form $form): Form
    {
        $form = parent::form($form);

        $form->schema([
            Forms\Components\Select::make('endpoint')
                ->options(OpenAIManagementBatch::$endpointOptions)
                ->required(),
            Forms\Components\TextInput::make('completion_window')
                ->required()
                ->default('24h')
                ->maxLength(255),
        ]);

        return $form;
    }
}
