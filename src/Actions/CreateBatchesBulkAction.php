<?php

namespace Moontechs\OpenAIManagement\Actions;

use Filament\Actions\Concerns\CanNotify;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Moontechs\OpenAIManagement\Forms\CreateBatchForm;
use Moontechs\OpenAIManagement\Models\OpenAIManagementFile;

class CreateBatchesBulkAction extends BulkAction
{
    use CanNotify;

    public static function getDefaultName(): ?string
    {
        return 'create_batches';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Create batches');

        $this->modalHeading(fn (): string => 'Create batches');

        $this->modalSubmitActionLabel(__('filament-actions::create.single.modal.actions.create.label'));

        $this->successNotificationTitle(__('filament-actions::create.single.notifications.created.title'));

        $this->deselectRecordsAfterCompletion(true);

        $this->color('primary');

        $this->groupedIcon(FilamentIcon::resolve('actions::create-action.grouped') ?? 'heroicon-m-plus-circle');

        $this->form(CreateBatchForm::getFormSchema());

        $this->action(function (array $data, Collection $records): void {
            /** @var OpenAIManagementFile $record */
            foreach ($records as $record) {
                $record->batches()->create([
                    'endpoint' => $data['endpoint'],
                    'completion_window' => $data['completion_window'],
                ]);
            }

            $this->success();
        });
    }
}
