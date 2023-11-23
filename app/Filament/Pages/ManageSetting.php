<?php

namespace App\Filament\Pages;

use App\Settings\SettingServerStorage;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageSetting extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $settings = SettingServerStorage::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('server_name')
                ->required(),
                TextInput::make('url')
                ->required(),
                TextInput::make('database_name')
                ->required(),
                TextInput::make('limit_database_mb')
                ->required(),
            ]);
    }
}
