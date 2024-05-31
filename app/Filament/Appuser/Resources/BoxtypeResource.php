<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\BoxtypeResource\Pages;
use App\Filament\Appuser\Resources\BoxtypeResource\RelationManagers;
use App\Models\Boxtype;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BoxtypeResource extends Resource
{
    protected static ?string $model = Boxtype::class;
    protected static ?string $navigationLabel = 'Box Type';
    public static ?string $label = 'Box Type';
    protected static ?string $navigationGroup = 'App Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('description')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('dimension')
                    ->maxLength(255),
                Forms\Components\TextInput::make('lenght')
                    ->maxLength(255),
                Forms\Components\TextInput::make('width')
                    ->maxLength(255),
                Forms\Components\TextInput::make('height')
                    ->maxLength(255),
                Forms\Components\TextInput::make('total_box')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('lenght'),
                Tables\Columns\TextColumn::make('width'),
                Tables\Columns\TextColumn::make('height'),
                Tables\Columns\TextColumn::make('dimension'),
                Tables\Columns\TextColumn::make('total_box'),
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
            'index' => Pages\ListBoxtypes::route('/'),
            'create' => Pages\CreateBoxtype::route('/create'),
            'edit' => Pages\EditBoxtype::route('/{record}/edit'),
        ];
    }
}
