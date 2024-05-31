<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Packlistitem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\PacklistitemResource\Pages;
use App\Filament\Appuser\Resources\PacklistitemResource\RelationManagers;

class PacklistitemResource extends Resource
{
    protected static ?string $model = Packlistitem::class;
    protected static ?string $navigationLabel = 'Packinglist Item';
    public static ?string $label = 'Packinglist Item';
    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Packinglist Item')->schema([
                    Forms\Components\TextInput::make('itemname')
                    ->label('Item Description')
                    ->required()
                    ->maxLength(255),
                ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('itemname'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListPacklistitems::route('/'),
            'create' => Pages\CreatePacklistitem::route('/create'),
            'edit' => Pages\EditPacklistitem::route('/{record}/edit'),
        ];
    }
}
