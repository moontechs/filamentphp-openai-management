<?php

return [
    'disk' => 'local',
    'directory' => 'openai-files',
    'download-disk' => 'local',
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
