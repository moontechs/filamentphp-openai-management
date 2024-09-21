<?php

namespace Moontechs\OpenAIManagement\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Moontechs\OpenAIManagement\Models\OpenAIManagementFile;
use Moontechs\OpenAIManagement\Models\OpenAIManagementProject;
use Moontechs\OpenAIManagement\OpenAI\ClientWrapper;
use OpenAI\Exceptions\ErrorException;

class OpenAIManagementFilesUpdateCommand extends Command
{
    public $signature = 'openai-management:files:update';

    public $description = 'Updates file statuses from OpenAI and uploads files to OpenAI';

    public function handle(): int
    {
        $this->info('Updating files...');

        foreach (OpenAIManagementProject::all() as $projectModel) {
            $this->info('Updating files from project: '.$projectModel->name);

            try {
                $openAIClient = ClientWrapper::make($projectModel)->getOpenAIClient();
            } catch (DecryptException $exception) {
                $this->error('Error decrypting project key: '.$projectModel->id.PHP_EOL.$exception->getMessage());
                Log::error('Error decrypting project key', [
                    'projectId' => $projectModel->id,
                    'error' => $exception->getMessage(),
                ]);

                continue;
            }

            $this->updateFiles($openAIClient, $projectModel);
        }

        $this->info('Files updated successfully!');

        $this->info('Uploading files...');

        foreach (OpenAIManagementProject::all() as $projectModel) {
            $this->info('Uploading files from project: '.$projectModel->name);

            try {
                $openAIClient = ClientWrapper::make($projectModel)->getOpenAIClient();
            } catch (DecryptException $exception) {
                $this->error('Error decrypting project key: '.$projectModel->id.PHP_EOL.$exception->getMessage());
                Log::error('Error decrypting project key', [
                    'projectId' => $projectModel->id,
                    'error' => $exception->getMessage(),
                ]);

                continue;
            }

            $this->uploadFiles($openAIClient, $projectModel);
        }

        $this->info('Files uploaded successfully!');

        return self::SUCCESS;
    }

    private function updateFiles(\OpenAI\Client $openAIClient, mixed $projectModel): void
    {
        try {
            $listResponse = $openAIClient->files()->list();
        } catch (ErrorException $exception) {
            $this->error('Error getting files list: '.$exception->getMessage());
            Log::error('Error getting files list', [
                'error' => $exception->getMessage(),
            ]);

            return;
        }

        foreach ($listResponse->data as $item) {
            $fileModel = OpenAIManagementFile::whereProjectId($projectModel->id)
                ->where('file_data->id', $item->id)
                ->first();

            if (! $fileModel) {
                continue;
            }

            $fileModel->file_data = $item->toArray();
            $fileModel->save();
        }
    }

    private function uploadFiles(\OpenAI\Client $openAIClient, mixed $projectModel)
    {
        /** @var OpenAIManagementFile[] $files */
        $files = OpenAIManagementFile::whereProjectId($projectModel->id)
            ->whereNull('file_data')
            ->get();

        foreach ($files as $fileModel) {
            if (! $fileModel->local_file_path_name) {
                $this->error('Error uploading file. File does not exist ID: '.$fileModel->id);
                Log::error('Error uploading file. File does not exist.', [
                    'fileId' => $fileModel->id,
                ]);

                continue;
            }

            try {
                $response = $openAIClient->files()->upload(
                    parameters: [
                        'purpose' => 'batch',
                        'file' => fopen(
                            Storage::disk(
                                config('filamentphp-openai-management.disk'),
                            )->path($fileModel->local_file_path_name),
                            'r',
                        ),
                    ]
                );
            } catch (ErrorException $exception) {
                $this->error('Error uploading a file: '.$exception->getMessage());
                Log::error('Error uploading a file', [
                    'fileId' => $fileModel->id,
                    'error' => $exception->getMessage(),
                ]);

                return;
            }

            if ($response->status === 'error') {
                $this->error('Error uploading file: '.$fileModel->local_file_path_name);
                Log::error('Error uploading file', [
                    'file' => $fileModel->local_file_path_name,
                    'response' => $response->toArray(),
                ]);

                continue;
            }

            $fileModel->file_data = $response->toArray();
            $fileModel->save();

            if ($fileModel->local_file_path_name) {
                Storage::disk(config('filamentphp-openai-management.disk'))->delete($fileModel->local_file_path_name);
            }
        }
    }
}
