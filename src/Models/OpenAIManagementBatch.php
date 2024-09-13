<?php

namespace Moontechs\OpenAIManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OpenAIManagementBatch extends Model
{
    use HasFactory;

    protected $table = 'openai_management_batches';

    protected $casts = [
        'batch_data' => 'array',
    ];

    protected $fillable = [
        'endpoint',
        'completion_window',
        'file_id',
        'batch_data',
        'created_at',
    ];

    protected static function booted(): void
    {
        static::deleted(function (OpenAIManagementBatch $batch) {
            if ($batch->batch_data !== null) {
                Storage::disk(config('openai-management.disk'))->delete($batch->getDownloadedFilePath());
            }
        });
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(OpenAIManagementFile::class, 'file_id');
    }

    public function getDownloadedFileFullPath(): string
    {
        return Storage::disk(config('openai-management.disk'))
            ->path($this->getDownloadedFilePath());
    }

    public function fileDownloaded(): bool
    {
        return File::exists($this->getDownloadedFileFullPath());
    }

    public function getDownloadedFilePath(): string
    {
        return config('openai-management.download-directory').'/'.Arr::get($this->batch_data, 'output_file_id').'.jsonl';
    }
}
