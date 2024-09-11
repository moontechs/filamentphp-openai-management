<?php

namespace Moontechs\OpenAIManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Moontechs\OpenAIManagement\OpenAI\ClientWrapper;

class OpenAIManagementFile extends Model
{
    use HasFactory;

    public static $purposeOptions = [
        'batch' => 'batch',
        'assistants' => 'assistants',
        'fine-tune' => 'fine-tune',
    ];

    protected $table = 'openai_management_files';

    protected $casts = [
        'file_data' => 'array',
        'batch_data' => 'array',
    ];

    protected $fillable = [
        'local_file_path_name',
        'uploaded_file_path_name',
        'purpose',
        'file_data',
        'batch_data',
        'project_id',
        'created_at',
    ];

    protected static function booted(): void
    {
        static::deleting(function (OpenAIManagementFile $fileModel) {
            $fileModel->batches()->delete();
        });

        static::deleted(function (OpenAIManagementFile $fileModel) {
            $openAIClient = ClientWrapper::make($fileModel->project)
                ->getOpenAIClient();

            if ($fileModel->file_data !== null) {
                $openAIClient->files()->delete($fileModel->file_data['id']);
            }

            if ($fileModel->local_file_path_name) {
                Storage::disk(config('openai-management.disk'))->delete($fileModel->local_file_path_name);
            }
        });
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(OpenAIManagementProject::class, 'project_id');
    }

    public function batches(): HasMany
    {
        return $this->hasMany(OpenAIManagementBatch::class, 'file_id');
    }
}