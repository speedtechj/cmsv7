<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Sender;
use App\Models\Booking;
use App\Models\Boxtype;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Cityphil;
use App\Models\Discount;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincecan;
use App\Models\Servicetype;
use App\Models\Transaction;
use Filament\Support\RawJs;
use App\Models\Barangayphil;
use App\Models\Provincephil;
use App\Models\Agentdiscount;
use App\Models\Senderaddress;
use App\Models\Catextracharge;
use App\Services\PriceService;
use Illuminate\Support\Number;
use App\Models\Receiveraddress;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use PHPUnit\Framework\Constraint\IsFalse;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\TransactionResource\Pages;
use App\Filament\Appuser\Resources\TransactionResource\RelationManagers;


class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Sender Information')
                    ->schema(static::getDetailsFormSchema())
                    ->columns('2'),
                Section::make('Transaction Information')
                    ->schema([
                        static::getItemsRepeater(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\Hidden::make('sender_id')
                ->default(request()->query('ownerRecord'))
                ->dehydrated(false),
            Forms\Components\Select::make('senderaddress_id')
                ->live()
                ->options(function (Get $get, Set $set, $state) {
                    return Senderaddress::Senderaddresslist($get('sender_id'));
                })
                ->label('Sender Address')
                ->required()
                ->dehydrated(false)
                ->afterStateUpdated(function ( Get $get, Set $set, $state) {
                  $quadrant = Senderaddress::where('id',$get('senderaddress_id'))->first()->quadrant;
                    $set('quadrant',$quadrant);
                }),
                Forms\Components\TextInput::make('quadrant')
                ->label('Quadrant')
                ->dehydrated(false)
                
        ];
    }
    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('booking')
            ->relationship()
            ->schema([
                Section::make()
                    ->schema([

                        Forms\Components\Select::make('receiver_id')
                            ->live()
                            ->options(function (Get $get, Set $set, $state) {
                                return Receiver::Receiverlist($get('../../sender_id'));

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
                                $set('province',Provincephil::Provincedisplay($get('receiveraddress_id')));
                                $set('city',Cityphil::Citydisplay($get('receiveraddress_id')));
                                $set('barangay',Barangayphil::Barangaydisplay($get('receiveraddress_id')));
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                            Forms\Components\TextInput::make('province')
                            ->dehydrated(false)
                            ->hidden(fn(Get $get): bool => $get('receiveraddress_id') == null),
                            Forms\Components\TextInput::make('city')
                            ->dehydrated(false)
                            ->hidden(fn(Get $get): bool => $get('receiveraddress_id') == null),
                            Forms\Components\TextInput::make('barangay')
                            ->dehydrated(false)
                            ->hidden(fn(Get $get): bool => $get('receiveraddress_id') == null),
                    ])->columns(2),
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('manual_invoice')
                        ->label('Manual Invoice')
                        ->unique(),
                        Forms\Components\Select::make('boxtype_id')
                            ->live()
                            ->options(Boxtype::all()->pluck('description', 'id'))
                            ->searchable()
                            ->searchPrompt('Please type to Search Box Type')
                            ->label('Box Type')
                            ->required()
                            ->selectablePlaceholder(false)
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                        Forms\Components\Select::make('servicetype_id')
                            ->live()
                            ->options(Servicetype::all()->pluck('description', 'id'))
                            ->label('Service Type')
                            ->required()
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->Resetdiscount($set, $get);
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                        Forms\Components\Select::make('agent_id')
                            ->live()
                            ->required()
                            ->searchable()
                            ->selectablePlaceholder(false)
                            ->preload()
                            ->searchPrompt('Please type to Search Agent')
                            ->label('Agent')
                            ->options(Agent::Agentlist()->all())
                            ->hidden(fn(Get $get): bool => $get('servicetype_id') == '2' || $get('servicetype_id') == null)
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->Resetdiscount($set, $get);
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                        Forms\Components\Hidden::make('zone_id')
                            ->required(),
                        Forms\Components\Hidden::make('branch_id')
                            ->required()
                            ->default(auth()->user()->branch_id),
                    ])->columns(2)
                    ->hidden(fn(Get $get): bool => $get('receiveraddress_id') == null),
                Section::make()
                    ->schema([
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
                            '07:00','08:00','09:00','10:00','11:00','12:00',
                            '13:00','14:00', '15:00','16:00','17:00','18:00',
                            '19:00','20:00','21:00','22:00'              
                        ])
                        
                            ->required(function(Get $get): bool {
                                $typeagent = Agent::Agenttype($get('agent_id'));
                               return $typeagent == 1;
                            })
                            ->prefix('Start')
                            ->label('Start Time')
                            ->visible(fn(Get $get): bool => $get('servicetype_id') == '1'),
                        Forms\Components\TimePicker::make('end_time')
                            ->prefix('End')
                            ->label('End Time')
                            ->datalist([
                                '07:00','08:00','09:00','10:00','11:00','12:00',
                                '13:00','14:00', '15:00','16:00','17:00','18:00',
                                '19:00','20:00','21:00','22:00'              
                            ])
                            ->required(function(Get $get): bool {
                                $typeagent = Agent::Agenttype($get('agent_id'));
                               return $typeagent == 1;
                            })
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
                            ->hidden(fn(Get $get): bool => $get('discount_flag') == '1')
                            // ->hidden(fn(Get $get): bool => false)
                            ->searchPrompt('Please type to Search Discount')
                            ->options(function (Get $get, Set $set, $state) {
                                return Agentdiscount::Agentdiscountlist($get('zone_id'), $get('agent_id'), $get('servicetype_id'), $get('boxtype_id'));
                            })
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->Agentdiscount($set, $get);
                                $priceService->calculatePrice($state, $get, $set);

                            }),
                            Forms\Components\Hidden::make('discount_flag')
                            ->default(1)
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('total_inches')
                            ->live(debounce: 1000)
                            ->numeric()
                            ->hidden(fn(Get $get): bool => $get('boxtype_id') != '9')
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                        
                        Forms\Components\TextInput::make(
                            'total_price'
                        )->prefix('$')
                            ->label('Total Price')
                            ->readOnly()
                            ->numeric()
                            ->columnSpanFull(),
                    ])->columns(3)
                    ->hidden(fn(Get $get): bool => $get('servicetype_id') == null),
                Section::make('Irregular Box Dimension')
                    ->schema([
                        Forms\Components\TextInput::make('irregular_length')
                            ->live(debounce: 1000)
                            ->numeric()
                            ->required()
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->Computeirregular($set, $get);
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                        Forms\Components\TextInput::make('irregular_width')
                            ->live(debounce: 1000)
                            ->numeric()
                            ->required()
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->Computeirregular($set, $get);
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                        Forms\Components\TextInput::make('irregular_height')
                            ->live(debounce: 1000)
                            ->numeric()
                            ->required()
                            ->afterStateUpdated(function (PriceService $priceService, Get $get, Set $set, $state) {
                                $priceService->Computeirregular($set, $get);
                                $priceService->calculatePrice($state, $get, $set);
                            }),
                    ])->columns(3)
                    ->hidden(fn(Get $get): bool => $get('boxtype_id') != '4'),
                Section::make()
                    ->schema([
                        Forms\Components\MarkdownEditor::make('note')
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_pickup')
                            ->required(),
                        Forms\Components\Toggle::make('box_replacement')
                            ->required(),
                    ])->columns(2)
                    ->hidden(fn(Get $get): bool => $get('servicetype_id') == null),


            ])->cloneable()
            ->addActionLabel('Add New Transaction')
            ->defaultItems(1)
            ->collapsible()
            ->mutateRelationshipDataBeforeCreateUsing(function (Get $get, array $data): array {
                $data['user_id'] = auth()->id();
                $data['sender_id'] = $get('sender_id');
                $data['senderaddress_id'] = $get('senderaddress_id');
                $data['payment_balance'] = $data['total_price'];
                // $data['manual_invoice'] = $get('manualinvoice');
                // $data['booked_date'] = $get('bookeddate');
                // $data['batch_id'] = $get('batchid');
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
                if($data['total_price'] == 0){
                    $data['is_paid'] = true;
                    $data['payment_date'] = $data['booking_date'];

                }
                return $data;
            });
            
    }
    
    
}
