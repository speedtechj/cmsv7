<?php

namespace App\Filament\Appuser\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Appuser\Resources\SearchinvoiceResource;

class CustomerhistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'customerhistory';
    public static ?string $title = 'Transaction History';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_id')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booking_id')
            ->defaultSort('booking_date', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                ->label('Invoice')
                ->sortable()
                ->searchable()
                ->color('primary')
                ->url(fn (Model $record) => SearchinvoiceResource::getUrl('view', ['record' => $record->id])),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('receiver.full_name')->label('Receiver')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('receiveraddress.address')->label('Address')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')->label('Province')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')->label('City')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\TextColumn::make('receiveraddress.barangayphil.name')->label('Barangay')
                    ->sortable()
                    ->searchable(),
                    Tables\Columns\BadgeColumn::make('servicetype.description')->label('Type of Service')
                    ->sortable()
                    ->color(static function ($state): string {
                        if ($state === 'Pickup') {
                            return 'success';
                        }

                        return 'info';
                    }),
                Tables\Columns\TextColumn::make('boxtype.description')->sortable(),
                Tables\Columns\TextColumn::make('booking_date')->label('Pickup/Dropoff Date')->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->sortable()
                    ->label('Paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
            ])
            ->filters([
                //
            ])
            ->headerActions([
              
            ])
            ->actions([
               
               
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                  
                ]),
            ]);
    }
}
