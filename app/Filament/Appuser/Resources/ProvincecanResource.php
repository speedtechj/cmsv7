<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincecan;
use Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\ProvincecanResource\Pages;
use App\Filament\Appuser\Resources\ProvincecanResource\RelationManagers;

class ProvincecanResource extends Resource {
    protected static ?string $model = Provincecan::class;
    protected static ?string $navigationGroup = 'Canada Location';
    protected static ?string $navigationLabel = 'Province';
    public static ?string $label = 'Canada Province';
    
    public static function form( Form $form ): Form {
        return $form
        ->schema( [
            Section::make( 'Canada Province Information' )
            ->schema( [
                Forms\Components\TextInput::make( 'province_code' )
                ->required()
                ->maxLength( 191 ),
                Forms\Components\TextInput::make( 'name' )
                ->required()
                ->maxLength( 191 ),
            ] )
        ] );
    }

    public static function table( Table $table ): Table {
        return $table
        ->columns( [
            Tables\Columns\TextColumn::make( 'province_code' )
            ->searchable(),
            Tables\Columns\TextColumn::make( 'name' )
            ->searchable(),
            Tables\Columns\TextColumn::make( 'created_at' )
            ->dateTime()
            ->sortable()
            ->toggleable( isToggledHiddenByDefault: true ),
            Tables\Columns\TextColumn::make( 'updated_at' )
            ->dateTime()
            ->sortable()
            ->toggleable( isToggledHiddenByDefault: true ),
        ] )
        ->filters( [
            //
        ] )
        ->actions( [
            Tables\Actions\EditAction::make(),
        ] )
        ->bulkActions( [
            Tables\Actions\BulkActionGroup::make( [
                Tables\Actions\DeleteBulkAction::make(),
            ] ),
        ] );
    }

    public static function getRelations(): array {
        return [
            //
        ];
    }

    public static function getPages(): array {
        return [
            'index' => Pages\ListProvincecans::route( '/' ),
            'create' => Pages\CreateProvincecan::route( '/create' ),
            'edit' => Pages\EditProvincecan::route( '/{record}/edit' ),
        ];
    }
}
