<?php

namespace App\Filament\Appuser\Resources\PosResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Branchcode;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PurchaseitemsRelationManager extends RelationManager
{
    protected static string $relationship = 'purchaseitems';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->defaultGroup('posinvoice.invoice_no')
        ->groups([
            Group::make('posinvoice.invoice_no')
            ->label('Invoice Number')
                ->collapsible(),
        ])
            ->recordTitleAttribute('sender_id')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_no')
                ->label('Invoice Number')
                ->searchable()
                ->getStateUsing(function ( Model $record){
                    $code = Branchcode::latest()->first();
                    return $code->storecode . $record->posinvoice->invoice_no;
                }),
                Tables\Columns\TextColumn::make('quantity')
                ->label('Quantity')
                ->searchable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->searchable(),
                Tables\Columns\TextColumn::make('boxtype.price')
                ->label('Price')
                ->money('CAD')
                ->label('Price'),
                Tables\Columns\TextColumn::make('delivery_chargge')
                ->money('CAD')
                ->label('Delivery Charge')
                ->default(function (Model $record) {
                    return $record->agent != null ? $record->boxtype->delivery_charge : null;
                  
                }),
                Tables\Columns\TextColumn::make('discount_amount')
                ->money('CAD')
                ->label('Discount Amount'),
                Tables\Columns\TextColumn::make('total_amount')
                ->money('CAD')
                ->label('Total Amount'),
                Tables\Columns\TextColumn::make('agent.full_name'),
                Tables\Columns\TextColumn::make('delivery_date')
               
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
