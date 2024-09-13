<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Moontechs\OpenAIManagement\Actions\DownloadProcessedFileAction;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;

class OpenAIManagementBatchRelationManager extends RelationManager
{
    protected static string $relationship = 'batches';

    protected static ?string $modelLabel = 'OpenAI Batch';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('endpoint')
                    ->options(config('openai-management.select-options.batch-endpoint'))
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('endpoint')
            ->columns([
                Tables\Columns\TextColumn::make('endpoint'),
                Tables\Columns\TextColumn::make('batch_data.status')
                    ->label('Batch status')
                    ->badge()
                    ->color(fn (OpenAIManagementBatch $record) => match ($record?->batch_data['status']) {
                        'completed' => 'success',
                        'error' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                DownloadProcessedFileAction::make(),
                Tables\Actions\ViewAction::make()->visible(fn ($record) => $record->batch_data !== null),
                Tables\Actions\EditAction::make()->visible(fn ($record) => $record->batch_data === null),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
