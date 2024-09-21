<?php

namespace Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource\Pages;

use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Moontechs\OpenAIManagement\Resources\OpenAIManagementProjectsResource;

class EditOpenAIManagementProjects extends EditRecord
{
    protected static string $resource = OpenAIManagementProjectsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Form $form): Form
    {
        $form = parent::form($form);

        $form->schema([
            TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('openai_project_id')
                ->label('OpenAI Project ID')
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('new_key')
                ->password(),
        ]);

        return $form;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (! empty($data['new_key'])) {
            $data['key'] = $data['new_key'];
        }

        return parent::handleRecordUpdate($record, $data);
    }
}
