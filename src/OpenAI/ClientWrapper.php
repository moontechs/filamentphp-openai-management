<?php

namespace Moontechs\OpenAIManagement\OpenAI;

use GuzzleHttp\Psr7\StreamWrapper;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Moontechs\OpenAIManagement\Models\OpenAIManagementProject;
use OpenAI;
use OpenAI\Client;

class ClientWrapper
{
    public function __construct(
        private readonly Client $openAIClient,
        private readonly \GuzzleHttp\Client $httpClient,
        private readonly string $key,
    ) {}

    public static function make(OpenAIManagementProject $projectModel): ClientWrapper
    {
        return new self(
            OpenAI::factory()
                ->withHttpHeader('OpenAI-Project', $projectModel->openai_project_id)
                ->withApiKey(Crypt::decrypt($projectModel->key))
                ->make(),
            new \GuzzleHttp\Client,
            $projectModel->key,
        );
    }

    public function downloadStreamTo(string $fileId, string $path): void
    {
        $response = $this->httpClient->request(
            'GET',
            "https://api.openai.com/v1/files/$fileId/content",
            [
                'headers' => [
                    'Authorization' => 'Bearer '.Crypt::decrypt($this->key),
                ],
                'stream' => true,
            ]
        );

        Storage::disk(config('filamentphp-openai-management.download-disk'))
            ->writeStream(
                $path,
                StreamWrapper::getResource(Utils::streamFor($response->getBody())),
            );
    }

    public function getOpenAIClient(): Client
    {
        return $this->openAIClient;
    }
}
