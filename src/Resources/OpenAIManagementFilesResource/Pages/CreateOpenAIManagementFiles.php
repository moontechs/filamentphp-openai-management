<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Moontechs\OpenAIManagement\Models\OpenAIManagementFile;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementFilesResource;

class CreateOpenAIManagementFiles extends CreateRecord
{
    protected static string $resource = OpenAIManagementFilesResource::class;

    // protected static ?string $title = 'Create OpenAI Files';

    public function form(Form $form): Form
    {
        $form = parent::form($form);

        $form->schema([
            Grid::make()->columns(2)->schema([
                Select::make('project_id')
                    ->required()
                    ->relationship('project', 'name'),
                Select::make('purpose')
                    ->options(OpenAIManagementFile::$purposeOptions)
                    ->required(),
            ]),

            Grid::make()->columns(1)->schema([
                FileUpload::make('files')
                    ->storeFileNamesIn('originalFileNames')
                    ->multiple()
                    ->disk(config('openai-management.disk'))
                    ->directory(config('openai-management.directory'))
                    ->required(),
            ]),

        ]);

        return $form;
    }

    protected function handleRecordCreation(array $data): Model
    {
        foreach ($data['files'] as $generatedFilePathName) {
            $last = OpenAIManagementFile::create([
                'local_file_path_name' => $generatedFilePathName,
                'uploaded_file_path_name' => $data['originalFileNames'][$generatedFilePathName],
                'project_id' => $data['project_id'],
                'purpose' => $data['purpose'],
            ]);
        }

        return $last;
    }
}
