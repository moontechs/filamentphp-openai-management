<?php

namespace Moontechs\OpenaiBatchesManagement\Commands;

use Illuminate\Console\Command;

class OpenaiBatchesManagementCommand extends Command
{
    public $signature = 'openai-batches-management';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
