<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Sender;
use App\Models\Booking;
use App\Models\Manifest;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincephil;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Exports\ManifestExporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\ExportBulkAction;
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
                ->toggleable(isToggledHiddenByDefault: true)
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
                ->toggleable(isToggledHiddenByDefault: true)
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
                ->wrap()
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.barangayphil.name')
                ->label('Barangay')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.provincephil.name')
                ->label('Province')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiveraddress.cityphil.name')
                ->label('City')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable()->sortable(),
            Tables\Columns\TextColumn::make('receiver.mobile_no')
                ->label('Mobile No')
                ->searchable()->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('receiver.home_no')
                ->label('Home No')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            
                Tables\Columns\TextColumn::make('sender.email')
                ->label('Home No')
                ->searchable()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                   SelectFilter::make('province')
                ->label('Province')
                ->options(
                    function () {
                        // could be more discerning here, and select a distinct list of aircraft id's
                        // that actually appear in the Daily Logs, so we aren't presenting filter options
                        // which don't exist in the table, but in my case we know they are all used
                        return Provincephil::all()->pluck('name', 'id')->toArray();
                    }
                )
                ->query(function (Builder $query, array $data) {
                    if (!empty($data['value'])) {
                        // if we have a value (the aircraft ID from our options() query), just query a nested
                        // set of whereHas() clauses to reach our target, in this case two deep
                        $query->whereHas(
                            'receiveraddress',
                            fn (Builder $query) => $query->whereHas(
                                'provincephil',
                                fn (Builder $query) => $query->where('id', '=', (int) $data['value'])
                            )
                        );
                    }
                }),
                SelectFilter::make('batch_id')
                ->searchable()
                ->preload()
                ->label('Batch Number')
                // ->options(Batch::all()->pluck('batchno', 'id'))
                ->relationship('batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
                ->getOptionLabelFromRecordUsing(function (Model $record) {
                    return "{$record->batchno} {$record->batch_year}";
                })
                // ->relationship('batch', 'batchno', fn (Builder $query) => $query->where('is_active', '1'))
                ->default('Select Batch Number'),
            ],
            layout: FiltersLayout::AboveContent
        )
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pl')
                ->label('Packing List')
                ->url(fn (Model $record) => SenderResource::getUrl('edit', ['record' => $record->sender_id]))
                ->visible(function (Model $record){
                   $plcount  = $record->packinglist->first()->packlistitem ?? 0;
                     if($plcount != null){
                          return false;
                     }else {
                            return true;
                     }
                }),
                Tables\Actions\Action::make('pl')
                ->label('PL Attachment')
                ->url(fn (Model $record) => SenderResource::getUrl('edit', ['record' => $record->sender_id]))
                ->color('info')
                ->visible(function (Model $record){
                    $plattachment  = $record->packinglist->first()->packlistdoc ?? null;
                    // dd($plattachment);
                      if($plattachment != null){
                           return false;
                      }else {
                             return true;
                      }
                 }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                        ExportBulkAction::make()
                        ->label('Export Manifest')
                        ->icon('heroicon-o-folder-arrow-down')
                        ->color('primary')
                        ->exporter(ManifestExporter::class)
                        ->fileName(fn (Export $export): string => "Manifest"),
                    Tables\Actions\BulkAction::make('Assign New Batch')
                ->visible(function (){
                    return auth()->user()->getRoleNames()->contains('super_admin');
                })
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
