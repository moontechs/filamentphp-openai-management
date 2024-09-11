<?php

namespace Moontechs\OpenAIManagement\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Moontechs\OpenAIManagement\OpenAI\ClientWrapper;

class DeleteFileFromOpenAIAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'delete_from_openai';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Delete from OpenAI only');

        $this->modalHeading(fn (): string => 'Delete from OpenAI');

        $this->modalSubmitActionLabel(__('filament-actions::delete.single.modal.actions.delete.label'));

        $this->successNotificationTitle(__('filament-actions::delete.single.notifications.deleted.title'));

        $this->color('danger');

        $this->groupedIcon(FilamentIcon::resolve('actions::delete-action.grouped') ?? 'heroicon-m-trash');

        $this->requiresConfirmation();

        $this->modalIcon(FilamentIcon::resolve('actions::delete-action.modal') ?? 'heroicon-o-trash');

        $this->action(function (): void {
            $result = $this->process(static function (Model $record) {
                if (! $record->file_data) {
                    return false;
                }

                $response = ClientWrapper::make($record->project)
                    ->getOpenAIClient()
                    ->files()
                    ->delete($record->file_data['id']);

                if (! $response->deleted) {
                    Log::error('Failed to delete file from OpenAI', [
                        'record_id' => $record->id,
                        'file_id' => $record->file_data['id'],
                        'response' => $response->toArray(),
                    ]);
                }

                if ($response->deleted) {
                    $record->file_data = null;
                    $record->save();
                }

                return $response->deleted;
            });

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }
}
