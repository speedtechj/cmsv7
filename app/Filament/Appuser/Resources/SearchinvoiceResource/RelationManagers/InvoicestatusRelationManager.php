<?php

namespace App\Filament\Appuser\Resources\SearchinvoiceResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class InvoicestatusRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicestatus';
    public static ?string $title = 'Invoice Status';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booking_id')
            ->columns([
                Tables\Columns\TextColumn::make('batach')
                ->label('Batch No')
                ->getStateUsing(fn ($record) => $record->batch->batchno . ' - ' . $record->batch->batch_year),
                Tables\Columns\TextColumn::make('trackstatus.description')
                ->label('Invoice Status'),
                Tables\Columns\TextColumn::make('date_update')
                ->label('Status Date')
                ->sortable(),
                Tables\Columns\TextColumn::make('location')
                ->label('Location'),
                Tables\Columns\TextColumn::make('waybill')
                ->label('Waybill'),
                Tables\Columns\TextColumn::make('remarks')
                ->label('Remarks'),
            ])->defaultSort('date_update', 'desc')
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
