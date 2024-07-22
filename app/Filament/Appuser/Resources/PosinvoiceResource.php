<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Agent;
use App\Models\Sender;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Posinvoice;
use Filament\Tables\Table;
use App\Models\Senderaddress;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Illuminate\Database\Eloquent\Builder;
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
                        ->dehydrated(false)
                        ->required()
                        ->live()
                        ->label('Customer Address')
                        ->options(Senderaddress::all()->where('sender_id',request()->query('ownerRecord'))->pluck('address', 'id'))
                        ->searchable()
                        ->selectablePlaceholder(false)
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            $senderaddress = Senderaddress::where('id', $state)->first();
                            $set('city', $senderaddress->citycan->name);
                            $set('province', $senderaddress->provincecan->name);
                            $set('postal_code', $senderaddress->postal_code);
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
                           if($state){
                               $set('agent_id', null);
                           }
                        }),
                        Forms\Components\Select::make('agent_id')
                        ->dehydrated(false)
                        ->hidden(fn (Get $get): bool => $get('delivery_date') == null)
                        ->label('Agent Name')
                        ->options(Agent::all()->pluck('full_name', 'id'))
                        ->searchable()
                        ->selectablePlaceholder(false)
                       
                ])->columns(3),
    //             Repeater::make('purchaseitems')
    // ->relationship()
    // ->schema([
    //     Forms\Components\TextInput::make('city')
    //     ->label('City')
    //     ->readOnly()
    //     ->dehydrated(false), 
    // ])


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
