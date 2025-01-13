<?php

namespace App\Filament\Willxpress\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Shippingbooking;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Willxpress\Resources\ShippingbookingResource\Pages;
use App\Filament\Willxpress\Resources\ShippingbookingResource\RelationManagers;

class ShippingbookingResource extends Resource
{
    protected static ?string $model = Shippingbooking::class;
    protected static ?string $navigationLabel = 'Shipping Monitoring';
    public static ?string $label = 'Shipping Monitoring';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';

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
        ->query(Shippingbooking::query()->where('branch_id', Auth::user()->branch_id))
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
                // Tables\Columns\TextColumn::make('shippingcontainer.batch.batchno')
                // ->label('Batch No')
                // ->listWithLineBreaks()
                // ->searchable(),
                Tables\Columns\TextColumn::make('bill_of_lading'),
                Tables\Columns\TextColumn::make('Bill_status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Request for Bill of Lading' => 'danger',
                    'Waiting for Bill of Lading' => 'warning',
                    'Completed' => 'success',
                })
                ->label('Bill of Lading Status')
                ->getStateUsing(function ($record) {
                    if($record->bill_of_lading != null){
                        return 'Completed';

                    }else {
                        $etd_date = Carbon::parse($record->etd);
                    $now = Carbon::now();
                    $diff = $etd_date->diffInDays($now, false);
                    if($diff > 14){
                        return'Request for Bill of Lading';
                    }else{
                        return 'Waiting for Bill of Lading';
                    }
                    };
                    
                }),
                Tables\Columns\TextColumn::make('eta')
                ->label('ETA'),
                // Tables\Columns\TextColumn::make('branch.business_name')
                // ->label('Broker')
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            // 'create' => Pages\CreateShippingbooking::route('/create'),
            // 'edit' => Pages\EditShippingbooking::route('/{record}/edit'),
        ];
    }
}
