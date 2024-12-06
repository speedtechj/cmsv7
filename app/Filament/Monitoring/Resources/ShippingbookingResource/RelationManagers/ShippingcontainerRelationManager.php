<?php

namespace App\Filament\Monitoring\Resources\ShippingbookingResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Batch;
use App\Models\Shippingbooking;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Skidweight;

use Filament\Tables\Table;

use App\Models\Skiddinginfo;
use Illuminate\Support\Number;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ShippingcontainerRelationManager extends RelationManager
{
    protected static string $relationship = 'shippingcontainer';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Container Information')
                            ->schema([
                                Forms\Components\Select::make('trucker_id')
                                    ->relationship('trucker', 'name')
                                    ->native(false)
                                    ->required(),
                                Forms\Components\Select::make('shippingbooking_id')
                                    ->relationship('shippingbooking', 'booking_no')
                                    ->native(false)
                                    ->required()
                                    ->visibleOn('edit'),
                                Forms\Components\Select::make('batch_id')
                                    ->live()
                                    ->native(false)
                                    ->relationship(
                                        'batch',
                                        'batchno',
                                        modifyQueryUsing: fn(Builder $query) => $query->where('is_lock', false)
                                    )
                                    ->required()
                                    ->afterStateUpdated(function (Set $set, $state) {
                                        $set('total_box', Skiddinginfo::where('batch_id', $state)->count());
                                        $set('total_cbm', Skiddinginfo::where('batch_id', $state)->sum('cbm'));
                                        $set('cargo_weight', Skidweight::where('batch_id', $state)->sum('weight'));

                                    }),
                                Forms\Components\TextInput::make('container_no')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('equipment_id')
                                    ->relationship('equipment', 'code')
                                    ->required()
                                    ->native(false),
                                Forms\Components\TextInput::make('seal_no')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255),

                            ])
                    ])->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Section::make('Star Weight / Cbm Information')
                            ->schema([
                                Forms\Components\TextInput::make('star_total_weight')
                                ->helperText('Please click the calculator icon to recalculate the total weight')
                                    ->label('Star Cargo Weight')
                                    ->prefix('lbs')
                                    ->suffixAction(
                                        Action::make('calculate_weight')
                                            ->icon('heroicon-o-calculator')
                                            ->label('Recalculate weight')
                                            ->requiresConfirmation()
                                            ->action(function (Set $set, Get $get, $state, RelationManager $livewire) {
                                              
                                                $set('cargo_weight', $state + $get('cargo_weight'));
                                            })
                                    )
                                   
                                    ->numeric()
                                    ->dehydrated(false),
                                Forms\Components\TextInput::make('star_total_box')
                                ->helperText('Please click the calculator icon to recalculate the total boxes')
                                    ->label('Star Total Box')
                                    ->prefix('boxes')
                                   
                                    ->numeric()
                                    ->dehydrated(false)
                                    ->suffixAction(
                                        Action::make('calculate_weight')
                                            ->icon('heroicon-o-calculator')
                                            ->label('Recalculate Total Box')
                                            ->requiresConfirmation()
                                            ->action(function (Set $set, $state, Get $get,RelationManager $livewired) {
                                                $set('total_box', $state + $get('total_box'));
                                            })
                                    ),
                                Forms\Components\TextInput::make('star_total_cbm')
                                    ->label('Star Total CBM')
                                    ->helperText('Please click the calculator icon to recalculate the total cbm')
                                    ->prefix('cbm')
                                    
                                    ->numeric()
                                    ->dehydrated(false)
                                    ->suffixAction(
                                        Action::make('calculate_weight')
                                            ->icon('heroicon-o-calculator')
                                            ->label('Recalculate Total cbm')
                                            ->requiresConfirmation()
                                            ->action(function (Set $set, $state, Get $get,RelationManager $livewired) {
                                                $set('total_cbm', $state + $get('total_cbm'));
                                            })
                                    ),
                            ])->columns(2),
                        Section::make('Weight / Cbm Information')
                            ->description(('Total weight, boxes and cbm of star and forex'))
                            ->schema([
                                Forms\Components\TextInput::make('tare_weight')
                                    ->suffix('lbs')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('cargo_weight')
                                    ->suffix('lbs')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('total_box')
                                    ->suffix('boxes')
                                    ->required()
                                    ->numeric(),
                                Forms\Components\TextInput::make('total_cbm')
                                    ->suffix('cbm')
                                    ->required()
                                    ->numeric(),
                            ])->columns(2),

                    ])->columnSpan(['lg' => 3]),
            ])->columns(5);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('container_no')
            ->columns([
                Tables\Columns\TextColumn::make('trucker.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('shippingbooking.booking_no')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batch.batchno')
                    ->searchable()
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('container_no')
                    ->searchable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('equipment.code')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('seal_no')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tare_weight')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargo_weight')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_box')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_cbm')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->toggleable(isToggledHiddenByDefault: true)
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (Model $record){
                    //   dd($this->getOwnerRecord()->branch_id);
                      $record->batch->update(['branch_id' => $this->getOwnerRecord()->branch_id]);
                   
                    })
                    ->slideOver()
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->mutateFormDataUsing(function (array $data): array {
                        
                        $data['user_id'] = auth()->id();
                        $data['branch_id'] = $this->getOwnerRecord()->branch_id;

                        return $data;
                       
                    }),
            ])
            ->actions([

                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                    ->after(function (Model $record){
                        
                        $record->update(['branch_id' => $this->getOwnerRecord()->branch_id]);
                       
                       
                        })
                        ->slideOver(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\Action::make('print')
                        ->label('Print Rail Bill')
                        ->color('warning')
                        ->icon('heroicon-o-printer')
                        ->url(fn(Model $record) => route('railbillinfos', $record))
                        ->openUrlInNewTab(),
                ])



            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
