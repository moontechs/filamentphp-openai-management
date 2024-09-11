<?php

namespace Moontechs\OpenAIManagement\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Moontechs\OpenAIManagement\Models\OpenAIManagementFile;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\RelationManagers\OpenAIManagementBatchRelationManager;

class OpenAIManagementFilesResource extends Resource
{
    protected static ?string $model = OpenAIManagementFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'OpenAI Files';

    protected static ?string $breadcrumb = 'OpenAI Files';

    protected static ?string $modelLabel = 'OpenAI File';

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
                Tables\Columns\TextColumn::make('project.name'),
                Tables\Columns\TextColumn::make('uploaded_file_path_name'),
                Tables\Columns\TextColumn::make('file_data.status')
                    ->label('File status')
                    ->badge()
                    ->color(fn (OpenAIManagementFile $record) => match ($record?->file_data['status']) {
                        'processed' => 'success',
                        'error' => 'danger',
                        default => 'warning',
                    }),
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
            OpenAIManagementBatchRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\Pages\ListOpenAIManagementFiles::route('/'),
            'create' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\Pages\CreateOpenAIManagementFiles::route('/create'),
            'edit' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\Pages\EditOpenAIManagementFiles::route('/{record}/edit'),
        ];
    }
}
