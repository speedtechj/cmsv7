<?php

namespace App\Filament\Appuser\Resources\SenderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Booking;
use App\Models\Boxtype;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Discount;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Packinglist;
use App\Models\Paymenttype;
use App\Models\Provincecan;
use App\Models\Servicetype;
use App\Models\Packlistitem;
use App\Models\Agentdiscount;
use App\Models\Senderaddress;
use App\Models\Bookingpayment;
use App\Models\Catextracharge;
use App\Services\PriceService;
use Filament\Facades\Filament;
use App\Models\Receiveraddress;
use Tables\Filters\TrashedFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use App\Filament\Appuser\Resources\AgentResource;
use App\Filament\Appuser\Resources\BookingResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\TransactionResource;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Appuser\Resources\SearchinvoiceResource;


class BookingRelationManager extends RelationManager
{
    protected static string $relationship = 'booking';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Sender Information')
                    ->schema(static::getSenderdetailsFormschema())
                    ->columnSpan('full'),
                Section::make('Transaction Information')
                    ->schema(static::getBookdetailsFormschema())->columns(3),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('booking_invoice')
            ->columns([
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->label('Invoice')
                    ->sortable()
                    ->searchable()
                    ->color('primary')
                    ->url(fn (Model $record) => SearchinvoiceResource::getUrl('view', ['record' => $record->id])),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->label('Manual Invoice')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('receiver.full_name')->label('Receiver')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('servicetype.description')->label('Type of Service')
                    ->color(static function ($state): string {
                        if ($state === 'Pickup') {
                            return 'success';
                        }

                        return 'info';
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('zone.description')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('booking_date')->label('Pickup/Dropoff Date')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('start_time')->label('Pickup Time')
                    ->getStateUsing(function (Model $record) {
                        return $record->start_time . " - " . $record->end_time;
                    })->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('dimension')->label('Dimension')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_inches')->label('No. of Inches')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('discount.discount_amount')->label('Discount')->money('USD', ) ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agentdiscount.discount_amount')->label('Agent Discount')->money('USD', ) ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('extracharge_amount')->money('USD') ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('total_price')->money('USD', ) ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('payment_date')->datetime()->label('Payment Date')->sortable()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->toggleable(isToggledHiddenByDefault: true),
                // ToggleColumn::make('is_padi'),
                Tables\Columns\TextColumn::make('payment_balance')->label('Balance')->money('USD') ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('refund_amount')->label('Refund')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('agent.full_name')->label('Agent')
                ->toggleable(isToggledHiddenByDefault: true)
                ->color('danger')
                ->url(fn (Model $record) => AgentResource::getUrl('edit', ['record' => $record->agent_id ?? 0])),
                // ->url(fn (Model $record) => AgentResource::getUrl('edit', $record->agent)),
                Tables\Columns\IconColumn::make('agent.agent_type')->label('In-House Agent')->boolean()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('note')->label('Notes')
                ->lineClamp(2)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.id')
                    ->label('Encoder')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return $record->user->first_name . " " . $record->user->last_name;
                    })
                    
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->searchOnBlur()
            ->persistSearchInSession()
        ->persistColumnSearchesInSession()
            ->filters([
                Tables\Filters\TrashedFilter::make()
                ->visible( fn (): bool => auth()->user()->isAdmin() ),
                Filter::make('is_paid')->label('Is Paid')->query(fn (Builder $query): Builder => $query->where('is_paid', false))->default(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(fn($livewire) => TransactionResource::getUrl('create', ['ownerRecord' => $livewire->ownerRecord->getKey()])),

            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['payment_balance'] = $data['total_price'];
                        if ($data['total_price'] == 0) {
                            $data['is_paid'] = true;
                            $data['payment_date'] = $data['booking_date'];

                        }

                        if ($data['servicetype_id'] == 2) {
                            $data['agent_id'] = null;
                            $data['start_time'] = null;
                            $data['end_time'] = null;
                        }
                        if ($data['boxtype_id'] != 4) {
                            $data['irregular_length'] = null;
                            $data['irregular_width'] = null;
                            $data['irregular_height'] = null;
                        }
                        return $data;
                    }),


                Tables\Actions\CreateAction::make()
                    ->url(fn($livewire) => TransactionResource::getUrl('create', ['ownerRecord' => $livewire->ownerRecord->getKey()])),

            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                Tables\Actions\EditAction::make()
                ->mutateRecordDataUsing(function (Model $record, array $data): array {
                    $reciveraddress = Receiveraddress::where('id', $record->receiveraddress_id)->first();
                    $data['province'] = $reciveraddress->provincephil->name;
                    $data['city'] = $reciveraddress->cityphil->name;
                    $data['barangay'] = $reciveraddress->barangayphil->name;
                    return $data;
                })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['payment_balance'] = $data['total_price'];
                        if ($data['total_price'] == 0) {
                            $data['is_paid'] = true;
                            $data['payment_date'] = $data['booking_date'];

                        }

                        if ($data['servicetype_id'] == 2) {
                            $data['agent_id'] = null;
                            $data['start_time'] = null;
                            $data['end_time'] = null;
                        }
                        if ($data['boxtype_id'] != 4) {
                            $data['irregular_length'] = null;
                            $data['irregular_width'] = null;
                            $data['irregular_height'] = null;
                        }
                        return $data;
                    }),
                Tables\Actions\DeleteAction::make()
                ->visible(function (Model $record): bool {
                    return $record->batch_id == 23;
                 }),
              
                    Tables\Actions\Action::make('Payment')
                        ->hidden(fn(Booking $record): bool => $record['payment_balance'] == 0)
                        ->model(Bookingpayment::class)
                        ->label('Received Payment')
                        ->icon('heroicon-o-pencil')
                        ->form([
                            Section::make()
                                ->schema(static::getPaymentform())

                        ])
                        ->action(function (Booking $record, array $data): void {
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
                                        ->title('Saved successfully')
                                        ->send();
                                } else {
                                    Notification::make()
                                        ->iconColor('danger')
                                        ->title('Amount Paid is greater than the balance')
                                        ->send();
                                }
                                $paid_is = $current_balance == 0 ? 1 : 0;
                                $record->update(['is_paid' => $paid_is]);
                            }
                        }),
                        Tables\Actions\Action::make('print')
                        ->label('Print Invoice')
                        ->color('warning')
                        ->icon('heroicon-o-printer')
                        ->url(fn (Booking $record) => route('barcode.pdf.download', $record))
                        ->openUrlInNewTab(),
                        Tables\Actions\Action::make('barcode')
                        ->color('success')
                        ->icon('heroicon-o-qr-code')
                        ->label('Print Barcode')
                        ->url(fn (Booking $record) => route('barcode1.pdf.download', $record))
                        ->openUrlInNewTab(),
                        Tables\Actions\Action::make('Packlist')
                        ->label('Packing list')
                        ->color('info')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->form([
                            Section::make()->schema(static::getPacklistform())->columns(3)
                        ])->action(function (Booking $record, array $data, $action) {
                            
                            Packinglist::create([
                                'booking_id' => $record->id,
                                'sender_id' => $record->sender_id,
                                'packlistitem' => $data['packinglist'],
                                'packlistdoc' => $data['packlist_doc'],
                                'waiverdoc' => $data['waiver_doc'],
                               
                            ]);
                           
                            Notification::make()
                            ->title('Record Successfully save')
                            ->success()
                            ->send();
                            
                        }),
                    ])
                        
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ForceDeleteBulkAction::make()
                    ->visible( fn (): bool => auth()->user()->isAdmin() ),
                    Tables\Actions\RestoreBulkAction::make()
                    ->visible( fn (): bool => auth()->user()->isAdmin() ),
                    Tables\Actions\DeleteBulkAction::make()
                    ->visible( fn (): bool => auth()->user()->isAdmin() ),
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
                    ->icon('heroicon-o-document-text')
                    ->color('primary')
                    ->action(function (Collection $records, array $data, $action) {
                        $records->each(function ($record) use ($data) {
                            if ($record->is_pickup == false) {
                                Booking::where('id', $record->id)->update([
                                    'is_pickup' => true,
                                ]);
                            }
                        });
                        Notification::make()
                                ->icon('heroicon-o-document-text')
                                ->iconColor('primary')
                                ->title('Successfully Update')
                                ->send();
                    }),
                         
                ]),
            ]);
    }
    public static function getSenderdetailsFormSchema(): array
    {
        return [
            Forms\Components\Select::make('senderaddress_id')
                ->options(function (RelationManager $livewire, Get $get, Set $set, $state) {
                    return Senderaddress::Senderaddresslist($livewire->ownerRecord->getKey());
                })
                ->label('Sender Address')
                ->required(),
        ];
    }
    public static function getBookdetailsFormschema(): array
    {
        return [
            Forms\Components\Select::make('receiver_id')
                ->live()
                ->options(function (Get $get, Set $set, $state) {
                    return Receiver::Receiverlist($get('sender_id'));

                })
                ->label('Receiver Name')
                ->required()
                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                    $set('receiveraddress_id', null);
                }),
            Forms\Components\Select::make('receiveraddress_id')
                ->label('Receiver Address')
                ->live()
                ->options(function (Get $get, Set $set, $state) {

                    return Receiveraddress::Receiveraddresslist($get('receiver_id'));
                })
                ->required()
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $reciveraddress = Receiveraddress::where('id', $state)->first();
                    $set('province',$reciveraddress->provincephil->name);
                    $set('city', $reciveraddress->cityphil->name);
                   $set('barangay', $reciveraddress->barangayphil->name ?? null);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\TextInput::make('province')
            ->dehydrated(false),
            Forms\Components\TextInput::make('city')
            ->dehydrated(false),
            Forms\Components\TextInput::make('barangay')
            ->dehydrated(false),
            Forms\Components\TextInput::make('manual_invoice')
            ->unique(ignoreRecord: true)
                ->label('Manual Invoice')
                ->visible(function (Model $record): bool {
                    return $record->batch_id == 23;
                 }),
            Forms\Components\Select::make('boxtype_id')
                ->live()
                ->options(Boxtype::all()->pluck('description', 'id'))
                ->searchable()
                ->searchPrompt('Please type to Search Box Type')
                ->label('Box Type')
                ->required()
                ->selectablePlaceholder(false)
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    if ($get('boxtype_id') == 4) {
                        $set('irregular_length', null);
                        $set('irregular_width', null);
                        $set('irregular_height', null);
                    }
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\Select::make('servicetype_id')
                ->live()
                ->options(Servicetype::all()->pluck('description', 'id'))
                ->label('Service Type')
                ->required()
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $set('start_time', null);
                    $set('end_time', null);
                    $priceService->Resetdiscount($set, $get);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\Select::make('agent_id')
                ->live()
                ->searchable()
                ->selectablePlaceholder(false)
                ->preload()
                ->searchPrompt('Please type to Search Agent')
                ->label('Agent')
                ->required()
                ->options(Agent::Agentlist()->all())
                ->hidden(fn(Get $get): bool => $get('servicetype_id') == '2' || $get('servicetype_id') == null)
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\Hidden::make('zone_id')
                ->required(),
            Forms\Components\Hidden::make('branch_id')
                ->required()
                ->default(auth()->user()->branch_id),
            Forms\Components\Hidden::make('batch_id')
                ->default(23),
            Forms\Components\DatePicker::make('booking_date')
                ->closeOnDateSelection()
                ->label('Booking Date')
                ->native(false)
                ->required()
                ->default(now()->format('Y-m-d')),
            Forms\Components\TimePicker::make('start_time')
                ->datalist([
                    '07:00',
                    '08:00',
                    '09:00',
                    '10:00',
                    '11:00',
                    '12:00',
                    '13:00',
                    '14:00',
                    '15:00',
                    '16:00',
                    '17:00',
                    '18:00',
                    '19:00',
                    '20:00',
                    '21:00',
                    '22:00'

                ])
                ->required(function (Get $get): bool {
                    $typeagent = Agent::Agenttype($get('agent_id'));
                    return $typeagent == 1;
                })
                ->prefix('Start')
                ->label('Start Time')
                ->visible(fn(Get $get): bool => $get('servicetype_id') == '1'),
            Forms\Components\TimePicker::make('end_time')
                ->prefix('End')
                ->datalist([
                    '07:00',
                    '08:00',
                    '09:00',
                    '10:00',
                    '11:00',
                    '12:00',
                    '13:00',
                    '14:00',
                    '15:00',
                    '16:00',
                    '17:00',
                    '18:00',
                    '19:00',
                    '20:00',
                    '21:00',
                    '22:00'

                ])
                ->required(function (Get $get): bool {
                    $typeagent = Agent::Agenttype($get('agent_id'));
                    return $typeagent == 1;
                })
                ->label('End Time')
                ->visible(fn(Get $get): bool => $get('servicetype_id') == '1'),
            Forms\Components\Select::make('catextracharge_id')
                ->live()
                ->options(Catextracharge::all()->pluck('name', 'id'))
                ->label('Extra Charge')
                ->searchable()
                ->searchPrompt('Please type to Search Extra Charge')
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->Resetdiscount($set, $get);
                    $priceService->Extracharge($set, $get, $state);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\TextInput::make('extracharge_amount')
                ->live(debounce: 1000)
                ->numeric()
                ->visible(fn(Get $get): bool => $get('catextracharge_id') != null)
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->Extracharge($set, $get, $state);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\Select::make('discount_id')
                ->live()
                ->label('Discount')
                ->searchable()
                ->hidden(fn(Get $get): bool => $get('discount_flag') == '0')
                ->searchPrompt('Please type to Search Discount')
                ->options(function (Get $get, Set $set, $state) {
                    return Discount::Officediscount($get('zone_id'), $get('servicetype_id'), $get('boxtype_id'));
                })
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->officediscount($set, $get);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\Select::make('agentdiscount_id')
                ->live()
                ->label('Agent Discount')
                ->searchable()
                ->hidden(fn(Get $get): bool => $get('discount_flag') != '0')
                ->searchPrompt('Please type to Search Discount')
                ->options(function (Get $get, Set $set, $state) {
                    return Agentdiscount::Agentdiscountlist($get('zone_id'), $get('agent_id'), $get('servicetype_id'), $get('boxtype_id'));
                })
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->Agentdiscount($set, $get);
                    $priceService->calculatePrice($state, $get, $set);

                }),
            Forms\Components\TextInput::make('total_inches')
                ->live(debounce: 1000)
                ->numeric()
                ->hidden(fn(Get $get): bool => $get('boxtype_id') != '9')
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\Hidden::make('discount_flag')
                ->default(1)
                ->dehydrated(false),
            Forms\Components\TextInput::make(
                'total_price'
            )->prefix('$')
                ->readOnly()
                ->label('Total Price')
                ->numeric()
                ->columnSpanFull(),
            Forms\Components\TextInput::make('irregular_length')
                ->live(debounce: 1000)
                ->numeric()
                ->visible(fn(Get $get): bool => $get('boxtype_id') == '4')
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->Computeirregular($set, $get);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\TextInput::make('irregular_width')
                ->live(debounce: 1000)
                ->numeric()
                ->visible(fn(Get $get): bool => $get('boxtype_id') == '4')
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->Computeirregular($set, $get);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\TextInput::make('irregular_height')
                ->live(debounce: 1000)
                ->numeric()
                ->visible(fn(Get $get): bool => $get('boxtype_id') == '4')
                ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                    $priceService->Computeirregular($set, $get);
                    $priceService->calculatePrice($state, $get, $set);
                }),
            Forms\Components\MarkdownEditor::make('note')
                ->columnSpanFull(),
            Forms\Components\Toggle::make('is_pickup')
                ->required(),
            Forms\Components\Toggle::make('box_replacement')
                ->required(),
        ];
    }
    public function getPaymentform(): array
    {
        return [
            Forms\Components\Select::make('type_of_payment')
                ->required()
                ->label('Mode Of Payment')
                ->options(Paymenttype::all()->pluck('name', 'id'))
                ->searchable()
                ->reactive(),
            Forms\Components\DatePicker::make('payment_date')->required()->default(now())
                ->native(false)
                ->closeOnDateSelection(),
            Forms\Components\TextInput::make('reference_number')->label('Authorization Code/Reference Number/Cheque Number')
                ->disabled(
                    fn(Get $get): bool => $get('type_of_payment') == 1
                ),

            Forms\Components\TextInput::make('Amount')->label('Payment Amount')
                ->required(),

            Forms\Components\TextInput::make('Booking_Balance')
                ->label('Amount Due')
                ->default(function (Booking $record) {
                    return $record->payment_balance;
                })->disabled(),
        ];
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
                    fn(Get $get): bool => $get('type_of_payment') == 1
                ),
        ];
    }

    public function getPacklistform() : array {
        return [
            Section::make('Attached Documents')
                            ->schema([
                                FileUpload::make('packlist_doc')
                                ->label('Packing List')
                                ->multiple()
                                ->enableDownload()
                                ->disk('public')
                                ->directory('packinglist')
                                ->visibility('private')
                                ->enableOpen(),
                            FileUpload::make('waiver_doc')
                                ->label(' Waiver')
                                ->multiple()
                                ->enableDownload()
                                ->disk('public')
                                ->directory('waiver')
                                ->visibility('private')
                                ->enableOpen(),
                            ])->columns(2),
                            Section::make('Details Packing List')
                            ->schema([
                                Repeater::make('packinglist')
                                ->schema([
                                    Forms\Components\TextInput::make('quantity')
                                    ->label('Quantity')
                                    ->numeric(),
                            Forms\Components\Select::make('packlistitem')
                                ->label('Premade Items')
                                ->options(Packlistitem::all()->pluck('itemname', 'itemname'))
                                ->searchable()
                                ->columnSpan('2')
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                            Forms\Components\TextInput::make('price')
                            ->label('Price')
                            ->prefix('$')
                            ->columnSpan('1'),
                                ])->columns(3)
                                ->minItems(1)
                                ->maxItems(3),
                                
                                ]),

        ];
    }
    public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);
}
}
