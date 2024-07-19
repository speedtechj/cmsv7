<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\PosResource\Pages;
use App\Filament\Appuser\Resources\PosResource\RelationManagers;
use App\Models\Pos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PosResource extends Resource
{
    protected static ?string $model = Pos::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->paginated([10, 25])
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('last_name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('full_name')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('mobile_no')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('home_no')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('email')
                ->searchable(isIndividual: true, isGlobal: false),
                
            ])
            ->filters([
                //
            ])
            ->actions([
               
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                  
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
            'index' => Pages\ListPos::route('/'),
            'create' => Pages\CreatePos::route('/create'),
            'edit' => Pages\EditPos::route('/{record}/edit'),
        ];
    }
}
