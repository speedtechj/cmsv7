<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\ProvincephilResource\Pages;
use App\Filament\Appuser\Resources\ProvincephilResource\RelationManagers;
use App\Filament\Appuser\Resources\ProvincephilResource\RelationManagers\CityphilRelationManager;
use App\Models\Provincephil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProvincephilResource extends Resource
{
    protected static ?string $model = Provincephil::class;
    protected static ?string $navigationGroup = 'Philippines Location';
    protected static ?string $navigationLabel = 'Province';
    public static ?string $label = 'Philippine Province';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
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
            CityphilRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProvincephils::route('/'),
            'create' => Pages\CreateProvincephil::route('/create'),
            'edit' => Pages\EditProvincephil::route('/{record}/edit'),
        ];
    }
}
