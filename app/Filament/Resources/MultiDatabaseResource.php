<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MultiDatabaseResource\Pages;
use App\Models\MultiDatabase;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MultiDatabaseResource extends Resource
{
    protected static ?string $model = MultiDatabase::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('host')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('database')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('has_database_name')
                    ->required()
                    ->dehydrateStateUsing(fn ($state) => Str::slug(Hash::make($state),"-"))
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('port')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255),
                Forms\Components\Select::make('status')->options([
                    '0' => 'không cho phép lưu',
                    '1' => 'cho phép lưu',
                ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('host')
                    ->searchable(),
                Tables\Columns\TextColumn::make('database')
                    ->searchable(),
                Tables\Columns\TextColumn::make('has_database_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('port')
                    ->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMultiDatabases::route('/'),
            'create' => Pages\CreateMultiDatabase::route('/create'),
            'edit' => Pages\EditMultiDatabase::route('/{record}/edit'),
        ];
    }
}
