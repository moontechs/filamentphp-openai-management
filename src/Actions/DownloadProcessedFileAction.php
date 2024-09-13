<?php

namespace Moontechs\OpenAIManagement\Actions;

use Closure;
use Filament\Support\Facades\FilamentIcon;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;

class DownloadProcessedFileAction extends Action
{
    protected ?Closure $mutateRecordDataUsing = null;

    public static function getDefaultName(): ?string
    {
        return 'download_processed_file';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Download');

        $this->color('black');

        $this->icon(FilamentIcon::resolve('actions::download-action') ?? 'heroicon-m-arrow-down-tray');

        $this->action(function (OpenAIManagementBatch $record) {
            return Storage::disk(config('filamentphp-openai-management.disk'))->download($record->getDownloadedFilePath());
        });

        $this->visible(function (OpenAIManagementBatch $record) {
            return Arr::get($record->batch_data, 'status') === 'completed' && $record->fileDownloaded();
        });
    }
}
