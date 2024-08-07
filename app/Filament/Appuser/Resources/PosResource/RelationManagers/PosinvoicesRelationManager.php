<?php

namespace App\Filament\Appuser\Resources\PosResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Branchcode;
use App\Models\Pospayment;
use Filament\Tables\Table;
use App\Models\Paymenttype;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\PosinvoiceResource;
use Filament\Resources\RelationManagers\RelationManager;

class PosinvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'posinvoices';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('sender_id')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_no')
                    ->label('Invoice Number')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        $code = Branchcode::latest()->first();
                        return $code->storecode . $record->invoice_no;
                    }),
                Tables\Columns\TextColumn::make('order_date')
                    ->label('Order Date')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->purchaseitems->first()->order_date;

                    }),
                Tables\Columns\TextColumn::make('senderaddress_id')
                    ->label('Sender Address')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->senderaddress->address;

                    }),
                Tables\Columns\TextColumn::make('total_box')
                    ->label('Total Box')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->purchaseitems->sum('quantity');

                    }),
                Tables\Columns\TextColumn::make('total_discount')
                    ->money('CAD')
                    ->label('Total Discount')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->purchaseitems->sum('discount_amount');

                    }),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('CAD')
                    ->label('Total Amount')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->purchaseitems->sum('total_amount');

                    }),

                Tables\Columns\TextColumn::make('total_balance')
                    ->money('CAD')
                    ->label('Total Balance')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->purchaseitems->sum('total_amount') - Pospayment::where('posinvoice_id', $record->id)->sum('payment_amount');

                    }),
                Tables\Columns\TextColumn::make('delivery_date')
                    ->label('Delivery Date')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {
                        return $record->purchaseitems->first()->delivery_date;

                    }),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->default(function (Model $record) {
                        return $record->purchaseitems->first()->is_paid;
                    }),
                Tables\Columns\TextColumn::make('agent_id')
                    ->label('Agent')
                    ->searchable()
                    ->getStateUsing(function (Model $record) {

                        $agentid = $record->purchaseitems->first()->agent_id;
                        return Agent::where('id', $agentid)->first()->full_name ?? null;
                    })
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->url(fn($livewire) => PosinvoiceResource::getUrl('create', ['ownerRecord' => $livewire->ownerRecord->getKey()])),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('print')
                        ->label('Print Invoice')
                        ->color('warning')
                        ->icon('heroicon-o-printer')
                        ->url(fn (Model $record) => route('posinvoice', $record))
                        ->openUrlInNewTab(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('Payment Received')
                        ->visible(fn(Model $record) => $record->purchaseitems->sum('total_amount') - Pospayment::where('posinvoice_id', $record->id)->sum('payment_amount') > 0)
                        ->icon('heroicon-o-banknotes')
                        ->action(function (Model $record, array $data) {
                            $data['sender_id'] = $record->sender_id;
                            $data['posinvoice_id'] = $record->id;
                            $data['user_id'] = auth()->id();
                            Pospayment::create($data);
                        })
                        ->modalHeading('Payment Received')
                        ->color('success')
                        ->modalIcon('heroicon-o-banknotes')
                        ->modalIconColor('warning')
                        ->slideOver()
                        ->form([
                            Forms\Components\Section::make('Payment Information')
                                ->schema([
                                    Forms\Components\Datepicker::make('payment_date')
                                        ->label('Payment Date')
                                        ->native(false)
                                        ->closeOnDateSelection()
                                        ->required(),
                                    Forms\Components\TextInput::make('reference_no')
                                        ->label('Reference')
                                        ->maxLength(255),
                                    Forms\Components\TextInput::make('payment_amount')
                                        ->live(debounce: 500)
                                        ->label('Payment Amount')
                                        ->numeric()
                                        ->required()
                                        ->prefix('CA$')
                                        ->rules([
                                            fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {

                                                if ($value > $get('balance_amount')) {
                                                    $fail("The Payment amount is greater than the balance amount owned");
                                                }
                                            },
                                        ]),
                                    Forms\Components\ToggleButtons::make('paymenttype_id')
                                        ->label('Payment Method')
                                        ->inline()
                                        ->options(
                                            Paymenttype::all()->pluck('name', 'id')
                                        )
                                        ->required(),
                                    Forms\Components\TextInput::make('balance_amount')
                                        ->prefix('CA$')
                                        ->dehydrated(false)
                                        ->readOnly()
                                        ->label('Balance Amount')
                                        ->numeric()
                                        ->default(function ($record) {
                                            $payment_total_amount = Pospayment::where('posinvoice_id', $record->id)->sum('payment_amount');
                                            return number_format($record->Purchaseitems->sum('total_amount') - $payment_total_amount, 2);
                                        }),
                                ])->columns(2),
                        ]),
                ])
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
