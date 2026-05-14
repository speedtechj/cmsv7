<?php

namespace App\Filament\Appuser\Resources;

use App\Filament\Appuser\Resources\BatchstatusResource\Pages;
use App\Filament\Appuser\Resources\BatchstatusResource\RelationManagers;
use App\Filament\Exports\BatchstatusExporter;
use App\Models\Batchstatus;
use App\Models\Invoicestatus;
use Filament\Actions\Exports\Models\Export;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class BatchstatusResource extends Resource
{
    protected static ?string $model = Batchstatus::class;

    protected static ?string $navigationLabel = 'Delivery Status';
    public static ?string $label = 'Delivery Status';

    protected static ?string $navigationIcon = 'heroicon-o-truck';
public static function getNavigationBadge(): ?string
{
    return "New";
}
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
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(10)
            ->modifyQueryUsing(function (Builder $query) {
                $query->whereIn('id', function ($sub) {
                    $sub->selectRaw('MAX(id)')
                        ->from('invoicestatuses')
                        ->groupBy('booking_id');
                });
            })
            ->columns([
                Tables\Columns\TextColumn::make('invoice')
                    ->label('Invoice')
                    ->searchable(['manual_invoice', 'generated_invoice'])
                    ->getStateUsing(function (Model $record) {
                        if ($record->manual_invoice != null) {
                            return $record->manual_invoice;
                        } else {
                            return $record->generated_invoice;
                        }
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function ($q) use ($search) {
                            $q->where('manual_invoice', 'like', "%{$search}%")
                                ->orWhere('generated_invoice', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('batch')
                    ->getStateUsing(function (Model $record) {
                        if ($record->batch) {
                            return $record->batch->batchno . ' ' . $record->batch->batch_year;
                        } else {
                            return 'N/A';
                        }
                    }),
                Tables\Columns\TextColumn::make('Latest Status')
                    ->label('Status')
                    ->separator(',')
                    ->color('primary')
                    ->listWithLineBreaks()
                    ->limitList(20)
                    //  ->expandableLimitedList()
                    ->getStateUsing(function ($record) {
                        $status = Invoicestatus::where('booking_id', $record->booking_id)
                            ->with('trackstatus')
                            ->latest('date_update') // latest by date_update
                            ->first();

                        return $status?->trackstatus->description;
                    }),
                Tables\Columns\TextColumn::make('StatusDate')
                    ->label('Status Date')
                    ->separator(',')
                    ->color('primary')
                    ->listWithLineBreaks()
                    ->limitList(20)
                    //  ->expandableLimitedList()
                    ->getStateUsing(function ($record) {
                        $status = Invoicestatus::where('booking_id', $record->booking_id)
                            ->with('trackstatus')
                            ->latest('date_update') // latest by date_update
                            ->first();

                        return $status?->date_update;
                    }),
                Tables\Columns\TextColumn::make('boxtype.description'),
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->label('Sender')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.full_name')
                    ->label('Receiver')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking.receiveraddress.address')
                    ->label('Receiver Address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('barangayphil.name')
                    ->label('Receiver Barangay'),
                Tables\Columns\TextColumn::make('cityphil.name')
                    ->label('Receiver City'),
                Tables\Columns\TextColumn::make('provincephil.name')
                    ->label('Receiver Province'),

            ])
            ->filters([
                SelectFilter::make('batchno')
                    ->label('Batch')
                    ->relationship('batch', 'batchno', fn(Builder $query) => $query->where('is_active', '1'))
                    ->getOptionLabelFromRecordUsing(function (Model $record) {
                        return "{$record->batchno} {$record->batch_year} ";
                    })->default('select batch'),
                TernaryFilter::make('delivery_status')
                    ->label('Delivery Status')
                    ->nullable()
                    ->trueLabel('Delivered')
                    ->falseLabel('Undelivered')
                    ->queries(
                        true: fn($query) => $query->whereHas('invoicestatuses', function ($q) {
                            $q->whereHas('trackstatus', function ($q2) {
                                $q2->where('code', 'ed');
                            });
                        }),

                        false: fn($query) => $query->whereDoesntHave('invoicestatuses', function ($q) {
                            $q->whereHas('trackstatus', function ($q2) {
                                $q2->where('code', 'ed');
                            });
                        }),
                    )

            ])
            ->headerActions([
            ExportAction::make()
                ->label('Export')
                ->exporter(BatchstatusExporter::class)
                ->color('info')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->fileName(fn (Export $export): string => "Delivery_Status_Export"),
        ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //   Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageBatchstatuses::route('/'),
        ];
    }
}
