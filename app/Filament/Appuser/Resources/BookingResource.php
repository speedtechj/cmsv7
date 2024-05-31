<?php

namespace App\Filament\Appuser\Resources;

use livewire;
use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Sender;
use App\Models\Booking;
use App\Models\Boxtype;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Discount;
use App\Models\Receiver;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Servicetype;
use App\Models\Senderaddress;
use App\Models\Catextracharge;
use App\Models\Receiveraddress;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Appuser\Resources\BookingResource\Pages;
use App\Filament\Appuser\Resources\BookingResource\RelationManagers;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Sender Information')
                    ->schema(static::getDetailsFormSchema())
                    ->columnSpan('full'),
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
                Tables\Columns\TextColumn::make('booking_invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('manual_invoice')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sender.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('sender.senderaddress.address')
                    ->limit(10)
                    ->tooltip(fn(Model $record): string => "{$record->senderaddress->address}")
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('receiver.receiveraddress.address')
                    ->limit(10)
                    ->tooltip(fn(Model $record): string => "{$record->receiveraddress->address}")
                    ->sortable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                    ->sortable(),
                Tables\Columns\TextColumn::make('servicetype.description')
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.full_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('zone.description')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('booking_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time'),
                Tables\Columns\TextColumn::make('end_time'),
                Tables\Columns\TextColumn::make('discount_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_pickup')
                    ->boolean(),
                Tables\Columns\TextColumn::make('total_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('irregular_length')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('irregular_width')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('irregular_height')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_inches')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean(),
                Tables\Columns\TextColumn::make('payment_balance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('refund_amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dimension')
                    ->searchable(),
                Tables\Columns\TextColumn::make('catextracharge_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('box_replacement')
                    ->boolean(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_edit')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_agent')
                    ->boolean(),
                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('agentdiscount_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_delivered')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
    public static function getDetailsFormSchema(): array
    {
        return [
            Forms\Components\Select::make('sender_id')
            ->searchable()
    ->getSearchResultsUsing(fn (string $search): array => Sender::where('full_name', 'like', "%{$search}%")->limit(100)->pluck('full_name', 'id')->toArray())
    ->getOptionLabelUsing(fn ($value): ?string => Sender::find($value)?->full_name),
            Forms\Components\Hidden::make('sender_id')
                ->required()
                ->default(request()->query('ownerRecord')),
            Forms\Components\Select::make('senderaddress_id')
                ->options(function (Get $get, Set $set, $state) {
                    return Senderaddress::all()->where('sender_id', $get('sender_id'))->pluck('address', 'id');
                })
                ->label('Sender Address')
                ->required(),
                
        ];
    }
    public static function getItemsRepeater(): Repeater
    {
        return Repeater::make('Transaction')
            ->schema([
                Section::make()
                    ->schema([
                       
                        Forms\Components\Select::make('receiver_id')
                            ->live()
                            ->options(function (Get $get, Set $set, $state) {
                                return Receiver::all()->where('sender_id', $get('../../sender_id'))->pluck('full_name', 'id');
                            })
                            ->label('Receiver Name')
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                $set('receiveraddress_id', null);
                                $set('receiveraddress_id', $state);
                                
                            }),

                        Forms\Components\Select::make('receiveraddress_id')
                            ->label('Receiver Address')
                            ->options(function (Get $get, Set $set, $state) {
                                return Receiveraddress::all()->where('receiver_id', $get('receiver_id'))->pluck('address', 'id');
                            })
                            ->required()
                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                               
                                
                            }),
                    ])->columns(2),
                
            ])->cloneable()
            ->addActionLabel('Add New Transaction')
            ->defaultItems(1)
            ->collapsible();
           
    }
}
