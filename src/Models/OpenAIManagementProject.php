<?php

namespace Moontechs\OpenAIManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class OpenAIManagementProject extends Model
{
    use HasFactory;

    protected $table = 'openai_management_projects';

    protected $fillable = [
        'name',
        'openai_project_id',
        'key',
    ];

    protected static function booted(): void
    {
        static::creating(function (OpenAIManagementProject $project) {
            $project->key = Crypt::encrypt($project->key);
        });
    }

    public function files(): HasMany
    {
        return $this->hasMany(OpenAIManagementFile::class, 'project_id');
    }
}
