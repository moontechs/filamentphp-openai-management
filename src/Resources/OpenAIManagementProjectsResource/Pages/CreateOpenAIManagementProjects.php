<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource;

class CreateOpenAIManagementProjects extends CreateRecord
{
    protected static string $resource = OpenAIManagementProjectsResource::class;

    // protected static ?string $title = 'Create OpenAI Project';

    public function form(Form $form): Form
    {
        $form = parent::form($form);

        $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(),
            TextInput::make('openai_project_id')
                ->label('OpenAI Project ID')
                ->required()
                ->unique(),
            TextInput::make('key')
                ->password()
                ->required(),
        ]);

        return $form;
    }
}
