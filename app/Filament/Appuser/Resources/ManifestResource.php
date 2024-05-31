<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Sender;
use App\Models\Booking;
use App\Models\Manifest;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\ManifestResource\Pages;
use App\Filament\Appuser\Resources\ManifestResource\RelationManagers;

class ManifestResource extends Resource
{
    protected static ?string $model = Manifest::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

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
                ->label('Invoice')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('manual_invoice')
                ->label('Manual Invoice')
                ->searchable()
                ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                ->label('Booking Date')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('Quantity')
                ->label('Quantity')
                ->default('1'),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('batch.id')
                ->label('Batch No')
                ->sortable()
                ->searchable()
                ->getStateUsing(function (Model $record) {
                    return $record->batch->batchno . "-" . $record->batch->batch_year;
                }),
            Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')
                ->searchable()
                ->sortable()
                ->url(fn (Model $record) => SenderResource::getUrl('edit', ['record' => $record->sender])),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver Name')
                ->searchable()
                ->sortable()
                ->url(fn (Model $record) => ReceiverResource::getUrl('edit', ['record' => $record->receiver])),
            Tables\Columns\TextColumn::make('receiveraddress.address')
                ->label('Address')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.barangayphil.name')
                ->label('Barangay')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                ->label('Province')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiver.mobile_no')
                ->label('Mobile No')
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiver.home_no')
                ->label('Home No')
                ->searchable()->sortable(),
            
            ])
            ->filters([
                SelectFilter::make('batch_id')
                ->multiple()
                ->label('Batch Number')
                ->options(Batch::Batchmanifest())
                // ->relationship('batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
                ->default(array('Select Batch Number')),
            ],
            layout: FiltersLayout::AboveContent
        )
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('Assign New Batch')
                ->color('danger')
                ->icon('heroicon-o-chevron-up-down')
                ->form([
                    Select::make('batch_id')
                        ->label('Batch')
                        ->relationship('batch', 'id', fn (Builder $query) => $query->where('is_lock', '0')->where('is_active', '1'))
                        ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->batchno} {$record->batch_year}")

                ])
                ->action(function (Collection $records, array $data): void {
                    foreach ($records as $record) {
                        $record->update([

                            'batch_id' => $data['batch_id'],
                        ]);
                    }
                })
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
            'index' => Pages\ListManifests::route('/'),
            // 'create' => Pages\CreateManifest::route('/create'),
            // 'edit' => Pages\EditManifest::route('/{record}/edit'),
        ];
    }
    
}
