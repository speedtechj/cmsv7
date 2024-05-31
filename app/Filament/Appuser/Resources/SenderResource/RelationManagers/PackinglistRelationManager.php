<?php

namespace App\Filament\Appuser\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Packlistitem;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class PackinglistRelationManager extends RelationManager
{
    protected static string $relationship = 'packinglist';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Attached Documents')
                    ->schema([
                        FileUpload::make('packlistdoc')
                            ->label('Packing List')
                            ->multiple()
                            ->enableDownload()
                            ->disk('public')
                            ->directory('packinglist')
                            ->visibility('private')
                            ->enableOpen(),
                        FileUpload::make('waiverdoc')
                            ->label('Waiver Document')
                            ->multiple()
                            ->enableDownload()
                            ->disk('public')
                            ->directory('waiver')
                            ->visibility('private')
                            ->enableOpen(),
                    ])->columns(2),
                Section::make('Details Packing List')
                    ->schema([
                        Repeater::make('packlistitem')
                            ->schema([
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric(),
                                Forms\Components\Select::make('packlistitem')
                                    ->label('Premade Items')
                                    ->options(Packlistitem::all()->pluck('itemname', 'itemname'))
                                    ->searchable()
                                    ->columnSpan('2'),
                                Forms\Components\TextInput::make('price')
                                    ->label('Price')
                                    ->prefix('$')
                                    ->columnSpan('1'),
                            ])->columns(3)
                            ->maxItems(3),

                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booking_id')
            ->columns([
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender'),
                Tables\Columns\TextColumn::make('booking.booking_invoice')
                    ->label('Booking Invoice'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
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
