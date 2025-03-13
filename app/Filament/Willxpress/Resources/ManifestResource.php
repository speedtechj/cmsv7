<?php

namespace App\Filament\Willxpress\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Manifest;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Shippingbooking;
use Filament\Resources\Resource;
use App\Models\Shippingcontainer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ManifestExporter;
use Filament\Actions\Exports\Models\Export;
use Filament\Tables\Actions\ExportBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Willxpress\Resources\ManifestResource\Pages;
use App\Filament\Willxpress\Resources\ManifestResource\RelationManagers;

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
                Tables\Columns\TextColumn::make('invoice')
                ->label('Invoice')
                ->searchable(['manual_invoice','booking_invoice'])
                ->sortable()
                ->getStateUsing(function (Model $record){
                    if($record->manual_invoice != null){
                        return $record->manual_invoice;
                    }else{
                        return $record->booking_invoice;
                    }
                }),
                Tables\Columns\TextColumn::make('invoice')
                ->label('Invoice')
                ->searchable(['manual_invoice','booking_invoice'])
                ->sortable()
                ->getStateUsing(function (Model $record){
                    if($record->manual_invoice != null){
                        return $record->manual_invoice;
                    }else{
                        return $record->booking_invoice;
                    }
                }),
            Tables\Columns\TextColumn::make('Quantity')
                ->label('Quantity')
                ->default('1'),
            Tables\Columns\TextColumn::make('boxtype.description')
                ->label('Box Type')
                ->searchable()
                ->sortable(),
            // Tables\Columns\TextColumn::make('batch.id')
            //     ->label('Batch No')
            //     ->sortable()
            //     ->searchable()
            //     ->getStateUsing(function (Model $record) {
            //         return $record->batch->batchno . "-" . $record->batch->batch_year;
            //     }),
            Tables\Columns\TextColumn::make('sender.full_name')
                ->label('Sender Name')
                ->searchable()
                ->sortable(),
                // ->url(fn (Model $record) => SenderResource::getUrl('edit', ['record' => $record->sender])),
            Tables\Columns\TextColumn::make('receiver.full_name')
                ->label('Receiver Name')
                ->searchable()
                ->sortable(),
                // ->url(fn (Model $record) => ReceiverResource::getUrl('edit', ['record' => $record->receiver])),
            Tables\Columns\TextColumn::make('receiveraddress.address')
                ->label('Address')
                ->searchable()
                ->sortable(),
            // Tables\Columns\TextColumn::make('receiveraddress.barangayphil.name')
            //     ->label('Barangay')
            //     ->searchable()->sortable(),
            // Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
            //     ->label('Province')
            //     ->searchable()->sortable(),
            // Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
            //     ->label('City')
            //     ->searchable()->sortable(),
            // Tables\Columns\TextColumn::make('receiver.mobile_no')
            //     ->label('Mobile No')
            //     ->searchable()->sortable(),
            // Tables\Columns\TextColumn::make('receiver.home_no')
            //     ->label('Home No')h
            //     ->searchable()->sortable(),
            ])
            ->filters([
                SelectFilter::make('batch_id')
                ->label('Container Number')
                ->searchable()
                ->options(function (Shippingcontainer $record) {
                    return $record->all()->where('is_active',1)->where('branch_id', Auth::user()->branch_id)->pluck('container_no', 'batch_id');
                })
                ->default()
            ],layout: FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\Action::make('manifest')
                ->color('warning')
                ->icon('heroicon-o-clipboard-document')
                ->label('Export Packinglist')
                ->url(fn (Model $record) => route('packlistdownload', $record))
                ->openUrlInNewTab(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                    ->label('Export Manifest')
                    ->icon('heroicon-o-folder-arrow-down')
                    ->color('primary')
                    ->exporter(ManifestExporter::class)
                    ->fileName(fn (Export $export): string => "Manifest")
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
            'create' => Pages\CreateManifest::route('/create'),
            'edit' => Pages\EditManifest::route('/{record}/edit'),
        ];
    }
}
