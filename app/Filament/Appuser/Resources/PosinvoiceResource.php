<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Sender;
use App\Models\Boxtype;
use App\Models\Citycan;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Posinvoice;
use Filament\Tables\Table;
use App\Models\Provincecan;
use App\Models\Senderaddress;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Appuser\Resources\PosinvoiceResource\Pages;
use App\Filament\Appuser\Resources\PosinvoiceResource\RelationManagers;
use App\Filament\Appuser\Resources\PosResource\RelationManagers\PosinvoicesRelationManager;

class PosinvoiceResource extends Resource
{
    protected static ?string $model = Posinvoice::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\Hidden::make('sender_id')
                            ->default(request()->query('ownerRecord')),
                        Forms\Components\TextInput::make('sendername')
                            ->required()
                            ->readOnly()
                            ->label('Customer Name')
                            ->dehydrated(false)
                            ->default(function () {
                                $sender_name = Sender::where('id', request()->query('ownerRecord'))->first();
                                return $sender_name->full_name;
                            }),
                        Forms\Components\Select::make('senderaddress_id')
                            ->required()
                            ->reactive()
                            ->label('Customer Address')
                            ->options(function (Get $get): array {
                                return Senderaddress::where('sender_id', $get('sender_id'))->get()->pluck('address', 'id')->toArray();
                            })
                            ->searchable()
                            ->selectablePlaceholder(false)
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $senderaddress = Senderaddress::where('id', $state)->first();
                                if ($senderaddress) {
                                    $set('city', $senderaddress->citycan->name);
                                    $set('province', $senderaddress->provincecan->name);
                                    $set('postal_code', $senderaddress->postal_code);
                                }

                            })
                            ->createOptionForm([
                                Forms\Components\TextInput::make('address')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('provincecan_id')
                                    ->label('Province')
                                    ->required()
                                    ->searchable()
                                    ->options(Provincecan::all()->pluck('name', 'id')->toArray())
                                    ->live(),
                                Forms\Components\Select::make('citycan_id')
                                    ->label('City')
                                    ->searchable()
                                    ->required()
                                    ->options(fn(Get $get): Collection => Citycan::query()
                                        ->where('provincecan_id', $get('provincecan_id'))
                                        ->pluck('name', 'id')),
                                Forms\Components\Select::make('quadrant')
                                    ->options([
                                        'NW' => 'North West',
                                        'SW' => 'South West',
                                        'NE' => 'North East',
                                        'SE' => 'South East',
                                    ]),
                                Forms\Components\TextInput::make('postal_code')
                                    ->mask('a9a 9a9')
                                    ->required()
                                    ->maxLength(255),

                            ])
                            ->createOptionUsing(function (array $data, Get $get) {
                                $data['user_id'] = auth()->id();
                                $data['sender_id'] = $get('sender_id');
                                Senderaddress::create($data);
                            })
                            ->createOptionAction(function (Action $action) {
                                return $action
                                    ->slideOver()
                                    ->modalHeading('Create customer')
                                    ->modalSubmitActionLabel('Create customer')
                                    ->modalWidth('lg');
                            }),

                        Forms\Components\TextInput::make('city')
                            ->label('City')
                            ->readOnly()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('province')
                            ->label('City')
                            ->readOnly()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->readOnly()
                            ->dehydrated(false),
                        Forms\Components\DatePicker::make('delivery_date')
                            ->dehydrated(false)
                            ->live()
                            ->label('Delivery Date')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->placeholder('Select a date')
                            ->afterStateUpdated(function (Set $set, $state) {
                                if($state == null){
                                    $set('agent_id', null);
                                    $set('purchaseitems', []);
                                }
                               
                            }),
                        Forms\Components\Select::make('agent_id')
                            ->dehydrated(false)
                            ->hidden(fn(Get $get): bool => $get('delivery_date') == null)
                            ->label('Agent Name')
                            ->options(Agent::all()->pluck('full_name', 'id'))
                            ->searchable()
                            ->selectablePlaceholder(false)

                    ])->columns(3),
                Repeater::make('purchaseitems')
                    ->relationship()
                    ->defaultItems(0)
                    ->schema([
                        Forms\Components\Section::make('Purchase Item')
                            ->schema([
                                Forms\Components\Select::make('boxtype_id')
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->selectablePlaceholder(false)
                                    ->live()
                                    ->label('Box Type')
                                    ->options(Boxtype::all()->where('code', 'forsale')->pluck('description', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                         $boxtype = Boxtype::where('id', $state)->first();
                                        if($get('../../agent_id')){
                                            $new_price = $boxtype->price + $boxtype->delivery_charge;
                                            $set('price', $new_price);
                                        }else {   
                                            $set('price', $boxtype->price);
                                        }
                                       
                                        
                                    }),
                                Forms\Components\TextInput::make('quantity')
                                    ->live(debounce: 500)
                                    ->label('Quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->required()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        $set('total_amount', $get('price') * $state);
                                    }),
                                Forms\Components\TextInput::make('discount_amount')
                                    ->live(debounce: 500)
                                    ->label('Discount Amount')
                                    ->numeric()
                                    ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                        $set('total_amount', ($get('price') * $get('quantity')) - $state);
                                    }),
                                    Forms\Components\TextInput::make('price')
                                    ->label('Price')
                                    ->readonly()
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('total_amount')
                                    ->label('Total Amount')
                                    ->readOnly()
                                    ->numeric()
                            ])->columns(5),


                    ])->columnSpan('full')
                    ->mutateRelationshipDataBeforeCreateUsing(function (array $data, Get $get): array {
                        $data['posinvoice_id'] = Posinvoice::latest()->first()->id;
                        $data['user_id'] = auth()->id();
                        $data['agent_id'] = $get('agent_id');
                        $data['sender_id'] = $get('sender_id');
                        $data['senderaddress_id'] = $get('senderaddress_id');
                        $data['delivery_date'] = $get('delivery_date');
                        $data['order_date'] = now();
                        return $data;
                    })

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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosinvoices::route('/'),
            'create' => Pages\CreatePosinvoice::route('/create'),
            'edit' => Pages\EditPosinvoice::route('/{record}/edit'),
        ];
    }
}
