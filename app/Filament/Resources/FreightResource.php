<?php

namespace App\Filament\Resources;

use Log;
use Filament\Forms;
use Filament\Tables;
use App\Models\Booking;
use App\Models\Freight;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Freightitem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\FreightResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\FreightResource\RelationManagers;

class FreightResource extends Resource
{
    protected static ?string $model = Freight::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Freight Information')
                    ->schema([
                        Forms\Components\TextInput::make('reference_no')
                        ->label('Reference/ Pro Bill Number')
                           
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('freight_date')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->required(),
                        Forms\Components\TextInput::make('freight_amount')
                            ->label('Freight Amount')
                            ->prefix('$')
                            ->suffix('CAD')
                            ->required()
                            ->numeric(),
                        Forms\Components\Select::make('agent_id')
                            ->label('Agent Name')
                            ->searchPrompt('Please type to Search Agent')
                            ->disabledOn('edit')
                            ->live()
                            ->preload()
                            ->searchable()
                            ->relationship('agent', 'full_name')
                            ->required()
                            ->afterStateUpdated(function (Set $set, Get $get, $state) {

                                $currentItems = $get('freightitem') ?? [];

                                $resetItems = collect($currentItems)->map(function ($item) {

                                    return [

                                        'booking_id' => '',
                                        'boxtype' => '',
                                    ];
                                })->toArray();

                                $set('freightitem', $resetItems);
                            }),
                        Forms\Components\FileUpload::make('invoices_attachment')
                            ->label('Invoice Attachment')
                            ->getUploadedFileNameForStorageUsing(function ($file, Get $get) {
                                return $get('reference_no'). '.' . $file->getClientOriginalExtension();
                            })
                            ->panelLayout('grid')
                            ->uploadingMessage('Uploading attachment...')
                            ->openable()
                            ->disk('public')
                            ->directory(function (Get $get) {
                                return 'freight/' . $get('agent_id');
                            })
                            ->visibility('private')
                            
                            ->removeUploadedFileButtonPosition('right'),
                    ])->columns(3),
                Section::make('Freight Item')
                   ->label('Invoice Item')
                    ->description(
                        'Please select the booking invoice that you want to charge.'
                    )
                    ->schema([
                        Forms\Components\Repeater::make('freightitem')
                            ->label('Invoice Item')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('manual'),
                                Forms\Components\Select::make('booking_id')
                                    ->required()
                                    ->live()
                                    ->unique(ignoreRecord: true)
                                    ->validationMessages([
                                        'unique' => 'This invoice has already been charged.',
                                    ])
                                    ->preload()  
                                    ->searchable()
                                    ->options(function (Get $get, Set $set, $state) {
                                        $agentId = $get('../../agent_id') ?? 0;
                                        return Booking::query()->where('agent_id',  $agentId)
                                            ->pluck('booking_invoice', 'id');
                                    })
                                    ->label('Booking Invoice')
                                    ->required()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(function (Set $set, $state) {

                                        $booking = Booking::where('id', $state)->first();
                                        $set('boxtype', $booking->boxtype->description ?? '');
                                    }),
                                Forms\Components\TextInput::make('boxtype')
                                    ->label('Box Type')
                                    ->readOnly()

                            ])
                            ->addActionLabel('Add New Item')
                            ->defaultItems(1)
                            ->columns(3)
                          





                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('freight_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('freight_amount')
                    ->label('Freight Amount')
                    ->formatStateUsing(fn($state) => '$' . number_format($state, 2))
                    ->sortable(),
                Tables\Columns\TextColumn::make('agent.full_name')
                    ->label('Agent Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Encoder')
                    ->numeric()
                    ->sortable(),
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
                SelectFilter::make('agent_id')
                    ->label('Agent Name')
                    ->searchable()
                    ->preload()
                    ->relationship('agent', 'full_name'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary'),
                    Tables\Actions\Action::make('Print')
                        ->color('info')
                        ->icon('heroicon-o-printer'),
                    Tables\Actions\Action::make('Send Email')
                        ->color('success')
                        ->icon('heroicon-o-envelope'),
                    Tables\Actions\Action::make('Received Payment')
                        ->color('warning')
                        ->icon('heroicon-o-banknotes'),
                    // ->url(fn (Freight $record) => route('freightpdf', $record))
                    // ->openUrlInNewTab(),
                    Tables\Actions\DeleteAction::make(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListFreights::route('/'),
            'create' => Pages\CreateFreight::route('/create'),
            'edit' => Pages\EditFreight::route('/{record}/edit'),
        ];
    }
}
