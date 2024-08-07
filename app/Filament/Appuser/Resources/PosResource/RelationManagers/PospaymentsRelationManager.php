<?php

namespace App\Filament\Appuser\Resources\PosResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Branchcode;
use Filament\Tables\Table;
use App\Models\Paymenttype;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PospaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'pospayments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                
                
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sender_id')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_no')
                ->label('Invoice Number')
                ->searchable()
                ->getStateUsing(function (Model $record) {
                    $code = Branchcode::latest()->first();
                    return $code->storecode . $record->posinvoice->invoice_no;
                }),
                Tables\Columns\TextColumn::make('payment_amount')
                
                ->label('Payment Amount')
                ->money('CAD')
                ->searchable(),
                Tables\Columns\TextColumn::make('payment_date')
                ->label('Payment Date'),
                Tables\Columns\TextColumn::make('paymenttype.name')
                    ->label('Payment Method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Paid By')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->slideOver()
                ->modalHeading('Create New Payment'),
            ])
            ->actions([
              
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
