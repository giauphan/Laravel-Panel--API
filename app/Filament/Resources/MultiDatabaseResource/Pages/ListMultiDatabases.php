<?php

namespace App\Filament\Resources\MultiDatabaseResource\Pages;

use App\Filament\Resources\MultiDatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMultiDatabases extends ListRecords
{
    protected static string $resource = MultiDatabaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
