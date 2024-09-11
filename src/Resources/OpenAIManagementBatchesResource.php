<?php

namespace Moontechs\OpenAIManagement\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;

class OpenAIManagementBatchesResource extends Resource
{
    protected static ?string $model = OpenAIManagementBatch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'OpenAI Batches';

    protected static ?string $breadcrumb = 'OpenAI Batches';

    protected static ?string $modelLabel = 'OpenAI Batch';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource\Pages\ListOpenAIManagementBatches::route('/'),
            'create' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource\Pages\CreateOpenAIManagementBatches::route('/create'),
            'edit' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementBatchesResource\Pages\EditOpenAIManagementBatches::route('/{record}/edit'),
        ];
    }
}
