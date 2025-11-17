<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\ZonerouteResource\Pages;
use App\Filament\Appuser\Resources\ZonerouteResource\RelationManagers;
use App\Models\Zoneroute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ZonerouteResource extends Resource
{
    protected static ?string $model = Zoneroute::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('route_code')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('route_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('route_code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('route_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListZoneroutes::route('/'),
            'create' => Pages\CreateZoneroute::route('/create'),
            'edit' => Pages\EditZoneroute::route('/{record}/edit'),
        ];
    }
}
