<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymenttypeResource\Pages;
use App\Filament\Resources\PaymenttypeResource\RelationManagers;
use App\Models\Paymenttype;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymenttypeResource extends Resource {
    protected static ?string $model = Paymenttype::class;
    protected static ?string $navigationLabel = 'Payment Type';
    public static ?string $label = 'Type of Payment';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function form( Form $form ): Form {
        return $form
        ->schema( [
            Forms\Components\TextInput::make( 'name' )
            ->required()
            ->maxLength( 255 ),
        ] );
    }

    public static function table( Table $table ): Table {
        return $table
        ->columns( [
            Tables\Columns\TextColumn::make( 'name' ),
            Tables\Columns\TextColumn::make( 'created_at' )
            ->dateTime(),
            Tables\Columns\TextColumn::make( 'updated_at' )
            ->dateTime(),
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
            'index' => Pages\ListPaymenttypes::route( '/' ),
            'create' => Pages\CreatePaymenttype::route( '/create' ),
            'edit' => Pages\EditPaymenttype::route( '/{record}/edit' ),
        ];
    }
}
