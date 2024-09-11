<?php

namespace Moontechs\OpenAIManagement\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Moontechs\OpenAIManagement\Models\OpenAIManagementBatch;
use Moontechs\OpenAIManagement\Models\OpenAIManagementProject;
use Moontechs\OpenAIManagement\OpenAI\ClientWrapper;
use OpenAI\Client;
use OpenAI\Exceptions\ErrorException;

class OpenAIManagementBatchesUpdateCommand extends Command
{
    public $signature = 'openai-management:batches:update';

    public $description = 'Updates batches statuses from OpenAI and creates a new one if needed';

    public function handle(): int
    {
        $this->info('Updating batches...');

        foreach (OpenAIManagementProject::all() as $projectModel) {
            $this->info('Updating batches from project: '.$projectModel->name);

            $openAIClient = ClientWrapper::make($projectModel)->getOpenAIClient();

            $this->updateBatches($openAIClient, $projectModel);
        }

        $this->info('Batches updated successfully!');

        $this->info('Creating batches...');

        foreach (OpenAIManagementProject::all() as $projectModel) {
            $this->info('Creating batches from project: '.$projectModel->name);

            $openAIClient = ClientWrapper::make($projectModel)->getOpenAIClient();

            $this->createBatch($openAIClient, $projectModel);
        }

        $this->info('Batches created!');

        return self::SUCCESS;
    }

    private function updateBatches(Client $openAIClient, mixed $projectModel): void
    {
        foreach ($projectModel->files as $fileModel) {
            foreach ($fileModel->batches as $batchModel) {
                if (! $batchModel->batch_data) {
                    continue;
                }

                $response = $openAIClient->batches()->retrieve($batchModel->batch_data['id']);

                $batchModel->batch_data = $response->toArray();
                $batchModel->save();
            }
        }
    }

    private function createBatch(Client $openAIClient, mixed $projectModel)
    {
        $batchInProgress = OpenAIManagementBatch::whereRelation('file', 'project_id', $projectModel->id)
            ->where('batch_data->status', '!=', 'failed')
            ->where('batch_data->status', '!=', 'completed')
            ->where('batch_data->status', '!=', 'cancelled')
            ->first();

        if ($batchInProgress) {
            $this->info('One batch is in process ID: '.$batchInProgress->id);
            Log::info('Batch in progress', [
                'batch' => $batchInProgress->id,
                'project' => $projectModel->name,
            ]);

            return;
        }

        $nextBatch = OpenAIManagementBatch::whereRelation('file', 'project_id', $projectModel->id)
            ->whereNull('batch_data')
            ->first();

        if (! $nextBatch) {
            $this->info('No batches for creation found');
            Log::info('No batches to create', [
                'project' => $projectModel->name,
            ]);

            return;
        }

        try {
            $response = $openAIClient->batches()->create(
                parameters: [
                    'input_file_id' => Arr::get($nextBatch->file->file_data, 'id'),
                    'endpoint' => $nextBatch->endpoint,
                    'completion_window' => $nextBatch->completion_window,
                ]
            );

            if ($response->errors === null) {
                $nextBatch->batch_data = $response->toArray();
                $nextBatch->save();

                return;
            }

            $this->error('Error creating batch ID: '.$nextBatch->id);

            Log::error('Error creating batch', [
                'fileId' => $nextBatch->file->id,
                'batchId' => $nextBatch->id,
                'response' => $response->toArray(),
            ]);
        } catch (ErrorException $exception) {
            $this->error('Error creating batch ID: '.$nextBatch->id.PHP_EOL.$exception->getMessage());
            Log::error('Error creating batch', [
                'fileId' => $nextBatch->file->id,
                'batchId' => $nextBatch->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
