<?php

namespace App\Filament\Appuser\Resources\PosResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\PosinvoiceResource;
use Filament\Resources\RelationManagers\RelationManager;

class PosinvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'posinvoices';

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
                Tables\Columns\TextColumn::make('senders.full_name'),
                Tables\Columns\TextColumn::make('invoice_no'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->url(fn($livewire) => PosinvoiceResource::getUrl('create', ['ownerRecord' => $livewire->ownerRecord->getKey()])),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
