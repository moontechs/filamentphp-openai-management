<?php

namespace Moontechs\OpenAIManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;
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
                Storage::disk(config('filamentphp-openai-management.download-disk'))->delete($batch->getDownloadedFilePathName());
            }
        });
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(OpenAIManagementFile::class, 'file_id');
    }

    public function fileDownloaded(): bool
    {
        return Storage::disk(config('filamentphp-openai-management.download-disk'))
            ->exists($this->getDownloadedFilePathName());
    }

    public function getDownloadedFilePathName(): string
    {
        return $this->getDownloadedFolderPath().'/'.$this->getDownloadedFileName();
    }

    public function getDownloadedFolderPath(): string
    {
        return config('filamentphp-openai-management.download-directory').'/'.$this->file->project->name;
    }

    public function getDownloadedFileName(): string
    {
        return Arr::get($this->batch_data, 'output_file_id').'.jsonl';
    }

    public function getFileNameForDownloadAction(): string
    {
        return 'processed_'.$this->file->uploaded_file_path_name;
    }
}
