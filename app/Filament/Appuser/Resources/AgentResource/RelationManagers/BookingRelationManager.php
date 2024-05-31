<?php

namespace App\Filament\Appuser\Resources\AgentResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Booking;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Paymenttype;
use App\Models\Packlistitem;
use App\Models\Bookingpayment;
use Filament\Facades\Filament;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use App\Filament\Exports\BookingExporter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Actions\ExportAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Actions\Exports\Enums\ExportFormat;
use App\Filament\Appuser\Resources\SenderResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\ReceiverResource;
use Filament\Resources\RelationManagers\RelationManager;

class BookingRelationManager extends RelationManager
{
    protected static string $relationship = 'booking';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('booking_invoice')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->recordTitleAttribute('booking_invoice')
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->label('Invoice')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextInputColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender.full_name')->label('Sender')
                    ->sortable()
                    ->searchable()
                    ->url(fn (Model $record) => SenderResource::getUrl('edit', ['record' => $record->sender])),
                Tables\Columns\TextColumn::make('receiver.full_name')->label('Receiver')
                    ->sortable()
                    ->searchable()
                    ->url(fn (Model $record) => ReceiverResource::getUrl('edit', ['record' => $record->receiver])),
                Tables\Columns\BadgeColumn::make('servicetype.description')->label('Type of Service')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(static function ($state): string {
                        if ($state === 'Pickup') {
                            return 'success';
                        }

                        return 'info';
                    }),
                Tables\Columns\TextColumn::make('boxtype.description'),
                Tables\Columns\TextColumn::make('batch.id')
                    ->label('Batch Number')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(function (Model $record) {
                        return $record->batch->batchno . " " . $record->batch->batch_year;
                    }),
                Tables\Columns\IconColumn::make('is_pickup')
                    ->label('Is Pickup')
                    ->boolean(),
                    Tables\Columns\TextColumn::make('senderaddress.quadrant')
                    ->sortable()
                    ->label('Quadrant'),
                Tables\Columns\TextColumn::make('zone.description'),
                
                Tables\Columns\TextColumn::make('booking_date')->label('Pickup/Dropoff Date')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_time')->label('Pickup/Dropoff Time')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->getStateUsing(function (Model $record) {
                        return $record->start_time . " - " . $record->end_time;
                    }),
                Tables\Columns\TextColumn::make('dimension')->label('Dimension')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_inches')->label('No. of Inches')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount.discount_amount')->label('Discount')->money('USD'),
                Tables\Columns\TextColumn::make('agentdiscount.discount_amount')->label('Agent Discount')->money('USD'),
                Tables\Columns\TextColumn::make('total_price')->money('USD'),
                Tables\Columns\TextColumn::make('payment_date')->date()->label('Payment Date')->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('payment_balance')->label('Balance')->money('USD'),
                Tables\Columns\TextColumn::make('refund_amount')->label('Refund')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agent.full_name')->label('Agent')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('agent.agent_type')->label('In-House Agent')->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('notes')->label('Notes'),
                

            ])
            ->filters([
                Filter::make('is_pickup')->query(fn(Builder $query): Builder => $query->where('is_pickup', false))->default(),
                Filter::make('booking_date')->label('Pickup Date')
                    ->form([
                        Section::make('Pickup Date')
                            ->schema([
                                Forms\Components\DatePicker::make('pickup_from'),
                                Forms\Components\DatePicker::make('pickup_until')
                            ])->collapsible(),

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['pickup_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('booking_date', '>=', $date),
                            )
                            ->when(
                                $data['pickup_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('booking_date', '<=', $date),
                            );
                    }),
                Filter::make('payment_date')->label('Payment Date')
                    ->form([
                        Section::make('Payment Date')
                            ->schema([
                                Forms\Components\DatePicker::make('payment_from'),
                                Forms\Components\DatePicker::make('payment_until'),
                            ])->collapsible()

                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['payment_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '>=', $date),
                            )
                            ->when(
                                $data['payment_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('payment_date', '<=', $date),
                            );
                    })
            ])
            ->headerActions([
                ExportAction::make()
                ->color('success')
                ->label('Export Booking')
                ->exporter(BookingExporter::class)
                ->fileDisk('local')
                ->formats([
                    ExportFormat::Xlsx,
                ])
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('print')
                        ->label('Print Invoice')
                        ->color('warning')
                        ->icon('heroicon-o-printer')
                        ->url(fn(Booking $record) => route('barcode.pdf.download', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('barcode')
                        ->color('success')
                        ->icon('heroicon-o-qr-code')
                        ->label('Print Barcode')
                        ->url(fn(Booking $record) => route('barcode1.pdf.download', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\Action::make('Payment')->label('Received Payment')
                        ->color('success')
                        ->icon('heroicon-o-currency-dollar')
                        ->hidden(fn(Booking $record) => $record->is_paid == 1)
                        ->form([
                            Select::make('type_of_payment')
                                ->required()
                                ->label('Mode Of Payment')
                                ->options(Paymenttype::all()->pluck('name', 'id'))
                                ->searchable()
                                ->reactive(),
                            DatePicker::make('payment_date')
                                ->native(false)
                                ->closeOnDateSelection()
                                ->required(),
                            TextInput::make('reference_number')->label('Authorization Code/Reference Number/Cheque Number')
                                ->disabled(
                                    fn(Get $get): bool => $get('type_of_payment') == 4
                                ),

                            TextInput::make('Amount')->label('Payment Amount')
                                ->prefix('$')
                                ->required(),
                            TextInput::make('Booking_Balance')
                                ->prefix('$')
                                ->label('Amount Due')
                                ->default(function (Booking $record) {
                                    return $record->payment_balance;
                                })
                                ->disabled(),
                        ])->action(function (Booking $record, array $data, $action) {

                            if ($record['payment_balance'] != 0) {
                                Bookingpayment::create([
                                    'booking_id' => $record->id,
                                    'paymenttype_id' => $data['type_of_payment'],
                                    'payment_date' => $data['payment_date'],
                                    'reference_number' => $data['reference_number'] ?? null,
                                    'booking_invoice' => $record['booking_invoice'],
                                    'payment_amount' => $data['Amount'],
                                    'user_id' => auth()->id(),
                                    'sender_id' => $record['sender_id'],
                                ]);
                                $record->update(['payment_date' => $data['payment_date']]);
                                $current_balance = $record['payment_balance'] - $data['Amount'];
                                if ($current_balance >= 0) {
                                    $record->update(['payment_balance' => $current_balance]);
                                    Notification::make()
                                        ->title('Payment Successful')
                                        ->color('success')
                                        ->success()
                                        ->send();

                                } else {
                                    Notification::make()
                                        ->title('Amount Paid is greater than the balance')
                                        ->color('danger')
                                        ->success()
                                        ->send();

                                }
                                $paid_is = $current_balance == 0 ? 1 : 0;
                                $record->update(['is_paid' => $paid_is]);
                            }
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make()
                ->label('Export Booking')
                ->icon('heroicon-o-document')
                ->color('success')
                ->exporter(BookingExporter::class)
                ->fileDisk('local')
                ->formats([
                    ExportFormat::Xlsx,
                ]),
                    Tables\Actions\BulkAction::make('Received Payment')
                    ->label('Received Payment')
                    ->color('warning')
                    ->icon('heroicon-o-currency-dollar')
                    ->form([
                        Section::make()
                            ->schema(static::getBulkpayment())

                    ])->action(function (Collection $records, array $data, $action) {
                        $records->each(function ($record) use ($data) {
                            if ($record->payment_balance != 0) {

                                Bookingpayment::create([
                                    'booking_id' => $record->id,
                                    'paymenttype_id' => $data['type_of_payment'],
                                    'payment_date' => $data['payment_date'],
                                    'reference_number' => $data['reference_number'] ?? null,
                                    'booking_invoice' => $record->booking_invoice,
                                    'payment_amount' => $record->payment_balance,
                                    'user_id' => auth()->id(),
                                    'sender_id' => $record->sender_id,
                                ]);
                                $record->update(['payment_date' => $data['payment_date']]);
                                Booking::where('id', $record->id)->update([
                                    'payment_balance' => 0,
                                    'is_paid' => true,
                                ]);

                            }
                        });
                        Notification::make()
                            ->icon('heroicon-o-document-text')
                            ->iconColor('success')
                            ->title('Payment Successfully received')
                            ->send();

                    }),
                    Tables\Actions\BulkAction::make('Update Pickup')
                    ->label('Pickup update')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('warning')
                    ->action(function (Collection $records, array $data, $action) {
                        $records->each(function ($record) use ($data) {
                            if ($record->is_pickup == false) {
                                Booking::where('id', $record->id)->update([
                                    'is_pickup' => true,
                                ]);
                            }
                        });
                        Notification::make()
                            ->icon('heroicon-o-clipboard-document-list')
                            ->iconColor('success')
                            ->title('Successfully Update')
                            ->send();

                    }),
                ]),
            ]);
    }
    public function getBulkpayment(): array
    {
        return [
            Forms\Components\Select::make('type_of_payment')
                ->required()
                ->label('Mode Of Payment')
                ->options(Paymenttype::all()->pluck('name', 'id'))
                ->searchable()
                ->reactive(),
            Forms\Components\DatePicker::make('payment_date')->required()->default(now())
                ->closeOnDateSelection(),
            TextInput::make('reference_number')->label('Authorization Code/Reference Number/Cheque Number')
                ->disabled(
                    fn(Get $get): bool => $get('type_of_payment') == 4
                ),
        ];
    }
}
