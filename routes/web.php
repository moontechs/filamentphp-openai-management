<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Moontechs\OpenAIManagement\Controllers\DownloadFileController;

Route::name('filament.')->group(function () {
    foreach (Filament::getPanels() as $panel) {
        $domains = $panel->getDomains();

        foreach ((empty($domains) ? [null] : $domains) as $domain) {
            Route::domain($domain)
                ->middleware(array_merge($panel->getMiddleware(), $panel->getAuthMiddleware()))
                ->name($panel->getId().'.')
                ->prefix($panel->getPath())
                ->group(function () use ($panel) {
                    if ($panel->hasPlugin('filamentphp-openai-management')) {
                        Route::get('open-a-i-management-batches/{id}/download', [DownloadFileController::class, 'downloadFile'])
                            ->name('filamentphp-openai-management.batch.download-file');
                    }
                });
        }
    }
});
