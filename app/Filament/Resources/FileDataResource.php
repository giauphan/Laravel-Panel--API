<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FileDataResource\Pages;
use App\Models\FileData;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class FileDataResource extends Resource
{
    protected static ?string $model = FileData::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('business_code')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('type_data')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('has_business_code')
                    ->required()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('Data')
                    ->required()
                    ->dehydrateStateUsing(fn ($state) => base64_encode($state))
                    ->maxLength(16777215)
                    ->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('business_code')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_data')
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
            'index' => Pages\ListFileData::route('/'),
            'create' => Pages\CreateFileData::route('/create'),
            'edit' => Pages\EditFileData::route('/{record}/edit'),
        ];
    }
}
