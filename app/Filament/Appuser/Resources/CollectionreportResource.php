<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Collectionreport;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use App\Exports\CollectionreportExport;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\CollectionreportResource\Pages;
use App\Filament\Appuser\Resources\CollectionreportResource\RelationManagers;

class CollectionreportResource extends Resource
{
    protected static ?string $model = Collectionreport::class;
    protected static ?string $navigationLabel = 'Collection Report';
    public static ?string $label = 'Collection Report';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Booking Generated Invoice')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Booking Manual Invoice')
                ->searchable(isIndividual: true, isGlobal: false),
                Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name'),
                Tables\Columns\TextColumn::make('senderaddress.address'),
                Tables\Columns\TextColumn::make('boxtype.description'),
                Tables\Columns\TextColumn::make('servicetype.description'),
                Tables\Columns\TextColumn::make('agent.full_name'),
                Tables\Columns\TextColumn::make('zone.description'),
                Tables\Columns\TextColumn::make('booking_date')
                ->label('Pickup/Dropoff Date'),
                Tables\Columns\TextColumn::make('discount.discount_amount'),
                Tables\Columns\TextColumn::make('extracharge_amount'),
                Tables\Columns\TextColumn::make('total_price')->money('USD'),
                Tables\Columns\IconColumn::make('is_paid')
                ->label('Paid')
                ->boolean(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->label('Payment Date'),
            ])
            ->filters([
                Filter::make('booking_date')->label('Booking Date')
                    ->form([
                        Section::make('Booking Date')
                            ->schema([
                                Forms\Components\DatePicker::make('book_from')->default(now())
                                ->closeOnDateSelection(),
                                Forms\Components\DatePicker::make('book_until')->default(now())
                                ->closeOnDateSelection(),
                            ])->collapsed(),
                        
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['book_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['book_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
                            );
                    }),   
                    SelectFilter::make('servicetype_id')->relationship('servicetype', 'description')->label('Service Type')->default('2'),
                    SelectFilter::make('agent_id')->relationship('agent', 'full_name')->label('Agent')->searchable(), 
                    Filter::make('payment_date')->label('Payment Date')
                    ->form([
                        Section::make('Payment Date')
                            ->schema([
                                Forms\Components\DatePicker::make('payment_from')
                                ->native(false)
                                ->closeOnDateSelection(),
                                Forms\Components\DatePicker::make('payment_until')
                                ->native(false)
                                ->closeOnDateSelection(),
                            ])->collapsed(),
                        
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payment_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['payment_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    }),
                    Filter::make('is_paid')->label('Is Paid')->query(fn (Builder $query): Builder => $query->where('is_paid', true))->default(false),
                     
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('xls')->label('Export to Excel')
                    ->icon('heroicon-o-arrow-down-on-square')
                    ->action(fn (Collection $records) => (new CollectionreportExport($records))->download('collection.xlsx')),
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
            'index' => Pages\ListCollectionreports::route('/'),
            'create' => Pages\CreateCollectionreport::route('/create'),
            // 'edit' => Pages\EditCollectionreport::route('/{record}/edit'),
        ];
    }
}
