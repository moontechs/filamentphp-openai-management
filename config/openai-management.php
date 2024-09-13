<?php

// config for Moontechs/OpenaiBatchesManagement
return [
    'disk' => 'local',
    'directory' => 'openai-files',
    'download-directory' => 'openai-files-downloads',

    'select-options' => [
        'file-purpose' => [
            'batch' => 'batch',
            'assistants' => 'assistants',
            'fine-tune' => 'fine-tune',
        ],
        'batch-endpoint' => [
            '/v1/chat/completions' => '/v1/chat/completions',
            '/v1/embeddings' => '/v1/embeddings',
            '/v1/completions' => '/v1/completions',
        ],
    ],
];
