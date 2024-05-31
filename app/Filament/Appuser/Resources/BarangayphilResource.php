<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Barangayphil;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\BarangayphilResource\Pages;
use App\Filament\Appuser\Resources\BarangayphilResource\RelationManagers;

class BarangayphilResource extends Resource
{
    protected static ?string $model = Barangayphil::class;
    protected static ?string $navigationGroup = 'Philippines Location';
    protected static ?string $navigationLabel = 'Barangay';
    public static ?string $label = 'Philippine Barangay';
  
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cityphil_id')
                ->relationship('Cityphil', 'name')->label('Philippines City'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cityphil.name')->label('City Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Barangay Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
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
            'index' => Pages\ListBarangayphils::route('/'),
            'create' => Pages\CreateBarangayphil::route('/create'),
            'edit' => Pages\EditBarangayphil::route('/{record}/edit'),
        ];
    }
}
