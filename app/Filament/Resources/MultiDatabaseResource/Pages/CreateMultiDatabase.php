<?php

namespace App\Filament\Resources\MultiDatabaseResource\Pages;

use App\Filament\Resources\MultiDatabaseResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMultiDatabase extends CreateRecord
{
    protected static string $resource = MultiDatabaseResource::class;
}
