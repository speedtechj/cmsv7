<?php

namespace App\Filament\Monitoring\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Shippingbooking;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Monitoring\Resources\ShippingbookingResource\Pages;
use App\Filament\Monitoring\Resources\ShippingbookingResource\RelationManagers;
use App\Filament\Monitoring\Resources\ShippingbookingResource\RelationManagers\ShippingcontainerRelationManager;

class ShippingbookingResource extends Resource
{
    protected static ?string $model = Shippingbooking::class;
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Shipping Booking';
    public static ?string $label = 'Shipping Booking';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                ->schema([
                    Section::make()
                    ->schema( [
                        Forms\Components\Select::make( 'shippingagent_id' )
                        ->label( 'Shipping Agent')
                        ->native(false)
                        ->relationship( 'shippingagent', 'company_name' )
                        ->required()
                        ->default(1),
                        Forms\Components\DatePicker::make( 'booking_date' )
                        ->native( false )
                        ->closeOnDateSelection()
                        ->label( 'Booking Date' )
                        ->required(),
                        Forms\Components\TextInput::make( 'booking_no' )
                        ->label( 'Booking Number' )
                        ->unique(ignoreRecord: true)
                        ->required()
                        ->maxLength( 255 ),
                        Forms\Components\Select::make( 'carrier_id' )
                        ->native(false)
                        ->relationship( 'carrier', 'name' )
                        ->required(),
                        Forms\Components\TextInput::make( 'vessel' )
                        ->label( 'Vessel/Voyage/(Flight No.)' )
                        ->required()
                        ->maxLength( 255 ),
                        Forms\Components\TextInput::make( 'commodity' )
                        ->label( 'Commodity' )
                        ->default('PERSONAL EFFECTS AND/OR USED HOUSEHOLD GOODS'),
                        Forms\Components\TextInput::make( 'hs_code' )
                        ->label( 'Hs Code' )
                        ->default('9804.00.45'),
                        Forms\Components\Select::make('branch_id' )
                        ->native(false)
                        ->label('Broker' )
                        ->relationship( 'branch', 'business_name' , modifyQueryUsing: fn (Builder $query) => $query->where('code', '=', 'broker'))
                        ->required(),
                        Forms\Components\TextInput::make( 'bill_of_lading' )
                        ->live()
                        ->label( 'Bill of Lading' )
                        ->unique(ignoreRecord: true)
                        ->maxLength( 255 )
                        ->afterStateUpdated(function (Set $set, $record, $state) {
                            if($state != null){
                                $set('is_complete', true);
                            }else {
                                $set('is_complete', false);
                            }
                            
                        }),
                        Forms\Components\Toggle::make('is_complete')
                        ->label('Is Complete'),
                        Forms\Components\MarkdownEditor::make( 'notes' )
                        ->label( 'Notes' )
                        ->columnSpanFull(),
                    ])->columns( 2 ),
                    
                ]) ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                ->schema([
                    Section::make()
                    ->schema([
                        Forms\Components\Select::make( 'return_terminal' )
                        ->native(false)
                        ->label( 'Return Terminal' )
                        ->options( [
                            'CN CALGARY' => 'CN CALGARY',
                            'CN EDMONTON' => 'CN EDMONTON',
                            'CN MONTREAL' => 'CN MONTREAL',
                            'CP CALGARY' => 'CP CALGARY',
                            'CP EDMONTON' => 'CP EDMONTON',
                            'CP MONTREAL' => 'CP MONTREAL'

                        ] )
                        ->required(),
        
                        Forms\Components\Select::make( 'origin_terminal' )
                        ->native(false)
                        ->options( [
                            'CALGARY' => 'CALGARY',
                            'EDMONTON' => 'EDMONTON',
                            'MONTREAL' => 'MONTREAL',
                        ] )
                        ->label( 'Origin Terminal' )
                        ->required(),
                        ]),
                        Section::make()
                        ->schema([
                        Forms\Components\Select::make( 'port_of_loading' )
                        ->label( 'Port of Loading' )
                        ->native(false)
                        ->options( [
                            'VANCOUVER' => 'VANCOUVER',
                            'HALIFAX' => 'HALIFAX',
                        ] ),
                        Forms\Components\Select::make( 'port_of_unloading' )
                        ->native(false)
                        ->label( 'Port of Unloading' )
                        ->required()
                        ->options( [
                            'MANILA NORTH HARBOR' => 'MANILA NORTH HARBOR',
                            'MANILA SOUTH HARBOR' => 'MANILA SOUTH HARBOR',
                        ] ),
                        Forms\Components\TextInput::make('place_of_receipt')
                        ->label('Place of Delivery')
                        ->required()
                        ->default('Manila')
                        ]),
                        Section::make()
                        ->schema([
                        Forms\Components\DatePicker::make( 'etd' )
                        ->closeOnDateSelection()
                        ->native( false )
                        ->label( 'Estimated Time of Departure' )
                        ->required(),
                        Forms\Components\DatePicker::make( 'eta' )
                        ->closeOnDateSelection()
                        ->native( false )
                        ->label( 'Estimated Time of Arrival' )
                        ->required(),
                        
                    ] )
                ]) ->columnSpan(['lg' => 1]),
            ])->columns( 3);;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make( 'shippingagent.company_name')
                ->label('Shipping Agent'),
                Tables\Columns\TextColumn::make( 'booking_date' )
                ->toggleable( isToggledHiddenByDefault: true )
                ->label( 'Booking Date' )
                ->sortable(),
                Tables\Columns\TextColumn::make( 'booking_no' )
                ->label( 'Booking Number' )
                ->searchable(),
                Tables\Columns\TextColumn::make( 'shippingcontainer.container_no' )
                ->label( 'Container Number' )
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make( 'shippingcontainer.seal_no' )
                ->label( 'Seal Number' )
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make( 'carrier.name' )
                ->label( 'Carrier' )
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make( 'vessel' )
                ->label( 'Vessel/Voyage/(Flight No.)' )
                ->toggleable( isToggledHiddenByDefault: true )
                ->numeric()
                ->sortable(),
                Tables\Columns\TextColumn::make( 'return_terminal' )
                ->toggleable( isToggledHiddenByDefault: true )
                ->searchable(),
                Tables\Columns\TextColumn::make( 'origin_terminal' )
                ->toggleable( isToggledHiddenByDefault: true )
                ->searchable(),
                Tables\Columns\TextColumn::make( 'port_of_loading' )
                ->toggleable( isToggledHiddenByDefault: true )
                ->searchable(),
                Tables\Columns\TextColumn::make( 'port_of_unloading' )
                ->toggleable( isToggledHiddenByDefault: true )
                ->searchable(),
                Tables\Columns\TextColumn::make( 'etd' )
                ->date()
                ->toggleable( isToggledHiddenByDefault: true )
                ->sortable(),
                Tables\Columns\TextColumn::make( 'eta' )
                ->date()
                ->toggleable( isToggledHiddenByDefault: true )
                ->sortable(),
                Tables\Columns\TextInputColumn::make( 'bill_of_lading' )
                ->rules(['required', 'max:255'])
                ->type('red')
                ->searchable()
                ->afterStateUpdated(function (Set $set, $record, $state) {
                    if($state != null){
                       $record->update(['is_complete' => true]);
                    }
                }),
                Tables\Columns\TextColumn::make('Bill_status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Request for Bill of Lading' => 'danger',
                    'Waiting for Bill of Lading' => 'warning',
                    'Completed' => 'success',
                })
                ->label('Bill of Lading Status')
                ->getStateUsing(function ($record) {
                    if($record->bill_of_lading != null){
                        return 'Completed';

                    }else {
                        $etd_date = Carbon::parse($record->etd);
                    $now = Carbon::now();
                    $diff = $etd_date->diffInDays($now, false);
                    if($diff > 14){
                        return'Request for Bill of Lading';
                    }else{
                        return 'Waiting for Bill of Lading';
                    }
                    };
                    
                }),
                Tables\Columns\TextColumn::make('branch.business_name')
                ->label('Broker')
                ->toggleable( isToggledHiddenByDefault: true ),
                Tables\Columns\TextColumn::make('shippingcontainer.batch.batchno')
                ->label('Batch No')
                ->listWithLineBreaks()
                ->searchable(),
                Tables\Columns\TextColumn::make( 'created_at' )
                ->dateTime()
                ->sortable()
                ->toggleable( isToggledHiddenByDefault: true ),
                Tables\Columns\ToggleColumn::make('is_complete')
                ->label('Complete'),
                Tables\Columns\TextColumn::make( 'updated_at' )
                ->dateTime()
                ->sortable()
                ->toggleable( isToggledHiddenByDefault: true ),
            ])
            ->filters([
                Filter::make('is_complete')
                ->label('Is Not Complete')
                ->toggle()
                ->query(fn (Builder $query): Builder => $query->where('is_complete', false)),
            ] )
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('print')
                                ->label('Print Shipping Instruction')
                                ->color('warning')
                                ->icon('heroicon-o-printer')
                                ->url(fn (Model $record) => route('instructionshipping', $record))
                                ->openUrlInNewTab(),
                    ])
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
            ShippingcontainerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingbookings::route('/'),
            'create' => Pages\CreateShippingbooking::route('/create'),
            'edit' => Pages\EditShippingbooking::route('/{record}/edit'),
        ];
    }
}
