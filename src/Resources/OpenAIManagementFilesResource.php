<?php

namespace Moontechs\OpenAIManagement\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Moontechs\OpenAIManagement\Models\OpenAIManagementFile;
use Moontechs\OpenAIManagement\Models\OpenAIManagementProject;
use Moontechs\OpenAIManagement\Repositories\OpenAIManagementFileRepository;
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
                Tables\Columns\TextColumn::make('uploaded_file_path_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('purpose')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tags')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('file_data.status')
                    ->label('File status')
                    ->badge()
                    ->searchable()
                    ->color(fn (OpenAIManagementFile $record) => match ($record?->file_data['status']) {
                        'processed' => 'success',
                        'error' => 'danger',
                        default => 'warning',
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(25)
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->options(fn () => OpenAIManagementProject::pluck('name', 'id')->toArray())
                    ->multiple(),
                Tables\Filters\SelectFilter::make('tags')
                    ->query(function ($query, $data) {
                        if (empty($data['values'])) {
                            return $query;
                        }

                        foreach ($data['values'] as $value) {
                            $query->whereJsonContains('tags', $value);
                        }

                        return $query;
                    })
                    ->options(fn (OpenAIManagementFileRepository $openAIManagementFileRepository) => $openAIManagementFileRepository->getUniqueTags())
                    ->multiple(),
                Tables\Filters\SelectFilter::make('purpose')
                    ->options(fn () => config('filamentphp-openai-management.select-options.file-purpose')),
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
