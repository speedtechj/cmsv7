<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Alltransaction;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\ExportBulkAction;
use App\Filament\Exports\AlltransactionExporter;
use Filament\Actions\Exports\Enums\ExportFormat;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AlltransactionResource\Pages;
use App\Filament\Resources\AlltransactionResource\RelationManagers;

class AlltransactionResource extends Resource
{
    protected static ?string $model = Alltransaction::class;

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
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender Name')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('senderaddress.address')
                    ->label('Sender Address')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('booking_invoice')
                    ->label('Invoice Number')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('boxtype.description')
                    ->label('Box Type')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('sender.mobile_no')
                    ->label('Mobile Number')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('sender.home_no')
                    ->label('Home Number')
                    ->searchable()
                    ->sortable(),
                    Tables\Columns\TextColumn::make('booking_date')
                    ->label('Transaction Date')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                    ->formats([
                        ExportFormat::Xlsx,
                    ])
                ->exporter(AlltransactionExporter::class)
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
            'index' => Pages\ListAlltransactions::route('/'),
            'create' => Pages\CreateAlltransaction::route('/create'),
            'edit' => Pages\EditAlltransaction::route('/{record}/edit'),
        ];
    }
}
