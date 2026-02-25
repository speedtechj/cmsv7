<?php

namespace App\Filament\Twelve24\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Shippingbooking;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Twelve24\Resources\ShippingbookingResource\Pages;
use App\Filament\Twelve24\Resources\ShippingbookingResource\RelationManagers;

class ShippingbookingResource extends Resource
{
    protected static ?string $model = Shippingbooking::class;
    protected static ?string $navigationLabel = 'Shipping Monitoring';
    public static ?string $label = 'Shipping Monitoring';

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
      //      ->modifyQueryUsing(fn (Builder $query) => $query->where('assign_to', true))
            ->columns([
                Tables\Columns\TextColumn::make( 'booking_no' )
                ->label( 'Booking Number' )
                ->searchable(),
                Tables\Columns\TextColumn::make( 'shippingcontainer.container_no' )
                ->label( 'Container Number' )
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make( 'shippingcontainer.seal_no' )
                ->label( 'Seal Number' )
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make( 'carrier.name' )
                ->label( 'Carrier' )
                ->numeric(),
                Tables\Columns\TextColumn::make('shippingcontainer.batch.batchno')
                ->label('Batch No')
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make('shippingcontainer.batch.batch_year')
                ->label('Batch Year')
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make('shippingcontainer.total_box')
                ->label('Total Box')
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make('shippingcontainer.equipment.code')
                ->label('Container Type')
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make('bill_of_lading'),
                Tables\Columns\TextColumn::make('eta')
                ->label('ETA'),
                Tables\Columns\TextColumn::make('branch.business_name')
                ->label('Broker')

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
            'index' => Pages\ListShippingbookings::route('/'),
            'create' => Pages\CreateShippingbooking::route('/create'),
            // 'edit' => Pages\EditShippingbooking::route('/{record}/edit'),
        ];
    }
}
