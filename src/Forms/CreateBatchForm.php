<?php

namespace Moontechs\OpenAIManagement\Forms;

use Filament\Forms;
use Illuminate\Support\Arr;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;

class CreateBatchForm
{
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('endpoint')
                ->options(config('filamentphp-openai-management.select-options.batch-endpoint'))
                ->required(),
            Forms\Components\TextInput::make('completion_window')
                ->required()
                ->default('24h')
                ->maxLength(255),
            Forms\Components\KeyValue::make('batch_data')
                ->visible(fn (string $context, ?OpenAIManagementBatch $record) => $context === 'view' && $record->batch_data !== null),
            Forms\Components\KeyValue::make('batch_data.request_counts')
                ->visible(fn (string $context, ?OpenAIManagementBatch $record) => $context === 'view' && $record->batch_data !== null),
            Forms\Components\KeyValue::make('batch_data.errors')
                ->visible(function (string $context, ?OpenAIManagementBatch $record) {
                    if ($context === 'view') {
                        if ($record !== null) {
                            return Arr::get($record->batch_data, 'errors') !== null;
                        }
                    }

                    return false;
                }),
        ];
    }
}
