<?php

namespace Moontechs\OpenAIManagement\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Moontechs\OpenAIManagement\Models\OpenAIManagementProject;

class OpenAIManagementProjectsResource extends Resource
{
    protected static ?string $model = OpenAIManagementProject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'OpenAI Projects';

    protected static ?string $breadcrumb = 'OpenAI Projects';

    protected static ?string $modelLabel = 'OpenAI Project';

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
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('openai_project_id')
                    ->label('OpenAI Project ID'),
            ])
            ->defaultPaginationPageOption(25)
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
            'index' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource\Pages\ListOpenAIManagementProjects::route('/'),
            'create' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource\Pages\CreateOpenAIManagementProjects::route('/create'),
            'edit' => \Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource\Pages\EditOpenAIManagementProjects::route('/{record}/edit'),
        ];
    }
}
