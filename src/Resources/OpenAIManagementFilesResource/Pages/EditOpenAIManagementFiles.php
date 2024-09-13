<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Moontechs\OpenAIManagement\Actions\DeleteFileFromOpenAIAction;
use Moontechs\OpenAIManagement\Repositories\OpenAIManagementFileRepository;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource;

class EditOpenAIManagementFiles extends EditRecord
{
    protected static string $resource = OpenAIManagementFilesResource::class;

    protected static ?string $title = 'Edit OpenAI File';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            DeleteFileFromOpenAIAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        $form = parent::form($form);

        $form->schema([
            Grid::make()->columns(2)->schema([
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->disabled(fn ($record) => $record->file_data !== null),
                Select::make('purpose')
                    ->options(config('openai-management.select-options.file-purpose'))
                    ->disabled(fn ($record) => $record->file_data !== null),
            ]),

            Grid::make()->columns(1)->schema([
                TagsInput::make('tags')->suggestions(function (OpenAIManagementFileRepository $openAIManagementFileRepository) {
                    return $openAIManagementFileRepository->getUniqueTags();
                }),
            ]),

            Grid::make()->columns(1)->schema([
                TextInput::make('uploaded_file_path_name')
                    ->disabled(),
            ]),

            KeyValue::make('file_data')
                ->disabled(),
        ]);

        return $form;
    }
}
