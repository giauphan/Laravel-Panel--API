<?php

namespace App\Filament\Resources\FileDataResource\Pages;

use App\Filament\Resources\FileDataResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFileData extends EditRecord
{
    protected static string $resource = FileDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
