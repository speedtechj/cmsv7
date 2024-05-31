<?php

namespace App\Filament\Appuser\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class BookingpaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'bookingpayment';

    public function form(Form $form): Form
    {
        return $form
            ->schema(static::getBookingpaymentform());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booking_invoice')
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice'),
                Tables\Columns\TextColumn::make('sender.full_name'),
                Tables\Columns\TextColumn::make('paymenttype.name'),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date(),
                Tables\Columns\TextColumn::make('payment_amount')->money('USD'),
                Tables\Columns\TextColumn::make('reference_number'),
                Tables\Columns\TextColumn::make('user_id')->label('Created By')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable()
                ->getStateUsing(function(Model $record) {
                    return $record->user->first_name ." " .$record->user->last_name;
                }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
               
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                ->visible(function (Get $get){
                   return auth()->user()->can('delete bookingpayment');
                })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
    public function getBookingpaymentform(): array {
        return [
            Forms\Components\Select::make('paymenttype_id')
                ->label('Payment Type')
                ->relationship('paymenttype', 'name'),
                Forms\Components\DatePicker::make('payment_date')
                ->native(false)
                ->closeOnDateSelection()
                ->label('Payment Date'),
                Forms\Components\TextInput::make('reference_number')
            
        ];
    }
}
