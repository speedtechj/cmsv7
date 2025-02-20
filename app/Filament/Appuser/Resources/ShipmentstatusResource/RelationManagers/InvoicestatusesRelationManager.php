<?php

namespace App\Filament\Appuser\Resources\ShipmentstatusResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Appuser\Resources\ShipmentstatusResource;

class InvoicestatusesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoicestatuses';

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
            ])->defaultSort('date_update', 'asc')
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Tables\Actions\Action::make('Back')
                ->url(fn($livewire) => ShipmentstatusResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
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
