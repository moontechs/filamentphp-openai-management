<?php

namespace Moontechs\OpenAIManagement\Repositories;

use Illuminate\Support\Facades\DB;

class OpenAIManagementFileRepository
{
    public function getUniqueTags(): array
    {
        return collect(DB::select(
            'select distinct value from openai_management_files, json_each(tags) order by value'
        ))->pluck('value')->mapWithKeys(function ($value) {
            return [$value => $value];
        })->toArray();
    }
}
