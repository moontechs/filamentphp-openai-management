<?php

namespace Moontechs\OpenAIManagement\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;
use Moontechs\OpenAIManagement\Models\OpenAIManagementProject;
use Moontechs\OpenAIManagement\OpenAI\ClientWrapper;

class OpenAIManagementProcessedFilesDownloadCommand extends Command
{
    public $signature = 'openai-management:processed-files:download';

    public $description = 'Downloads processed files from OpenAI';

    public function handle(): int
    {
        $this->info('Downloading files...');

        if (! Storage::disk(config('openai-management.disk'))->exists(config('openai-management.download-directory'))) {
            Storage::disk(config('openai-management.disk'))->makeDirectory(config('openai-management.download-directory'));
        }

        foreach (OpenAIManagementProject::all() as $projectModel) {
            $this->info('Downloading files from project: '.$projectModel->name);

            $openAIClientWrapper = ClientWrapper::make($projectModel);

            $this->downloadFiles($openAIClientWrapper, $projectModel);
        }

        $this->info('Files downloaded successfully!');

        return self::SUCCESS;
    }

    private function downloadFiles(ClientWrapper $openAIClientWrapper, mixed $projectModel): void
    {
        /** @var OpenAIManagementBatch[] $batches */
        $batches = OpenAIManagementBatch::whereRelation('file', 'project_id', $projectModel->id)
            ->whereNotNull('batch_data')
            ->where('batch_data->output_file_id', '!=', null)
            ->where('batch_data->status', '=', 'completed')
            ->get();

        foreach ($batches as $batchModel) {
            $filePath = $batchModel->getDownloadedFilePath();

            if ($batchModel->fileDownloaded()) {
                $this->info('File already exists: '.$batchModel->batch_data['output_file_id']);

                continue;
            }

            $openAIClientWrapper->downloadStreamTo(
                $batchModel->batch_data['output_file_id'],
                Storage::disk(config('openai-management.disk'))->path($filePath),
            );
        }
    }
}
