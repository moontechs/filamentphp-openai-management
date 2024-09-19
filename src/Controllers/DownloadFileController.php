<?php

namespace Moontechs\OpenAIManagement\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;

class DownloadFileController extends Controller
{
    public function downloadFile(int $batchId)
    {
        /** @var OpenAIManagementBatch $record */
        $record = OpenAIManagementBatch::findOrFail($batchId);

        if (ob_get_level()) {
            ob_end_clean();
        }

        $callback = function () use ($record) {
            $stream = Storage::disk(config('filamentphp-openai-management.download-disk'))->readStream($record->getDownloadedFilePathName());
            fpassthru($stream);
            fclose($stream);
        };

        return response()->streamDownload($callback, $record->getFileNameForDownloadAction());
    }
}
