<?php

namespace App\Filament\Resources\FileDataResource\Pages;

use App\Filament\Resources\FileDataResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFileData extends ListRecords
{
    protected static string $resource = FileDataResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
