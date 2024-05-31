<?php

namespace App\Filament\Monitoring\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Citycan;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincecan;
use App\Models\Shippingagent;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Monitoring\Resources\ShippingagentResource\Pages;
use App\Filament\Monitoring\Resources\ShippingagentResource\RelationManagers;

class ShippingagentResource extends Resource
{
    protected static ?string $model = Shippingagent::class;

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Shipping Agent';
    public static ?string $label = 'Shipping Agents';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Shipping Agent Information')
                    ->schema([
                        Forms\Components\TextInput::make('company_name')
                            ->label('Company Name')
                            ->required(),
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->required(),
                        Forms\Components\Select::make('provincecan_id')
                            ->live()
                            ->label('Province')
                            ->options(Provincecan::all()->pluck('name', 'id'))
                            ->required(),
                        Forms\Components\Select::make('citycan_id')
                            ->live()
                            ->label('City')
                            ->options(fn(Get $get): Collection => Citycan::query()
                                ->where('provincecan_id', $get('provincecan_id'))
                                ->pluck('name', 'id'))
                            ->required(),
                        Forms\Components\TextInput::make('postal_code')
                            ->label('Postal Code')
                            ->mask('a9a 9a9')
                            ->stripCharacters(' ')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Contact Number'),
                        Forms\Components\TextInput::make('email')
                            ->label('Email Address'),
                        Forms\Components\TextInput::make('contact_person')
                            ->label('Contact Person'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company_name'),
                Tables\Columns\TextColumn::make('address'),
                Tables\Columns\TextColumn::make('provincecan.name')
                    ->label('Province'),
                Tables\Columns\TextColumn::make('citycan.name')
                ->label('City'),
                Tables\Columns\TextColumn::make('postal_code'),
                Tables\Columns\TextColumn::make('phone')
                ->label('Contact Number'),
                Tables\Columns\TextColumn::make('email')
                ->label('Email Address'),
                Tables\Columns\TextColumn::make('contact_person')
                ->label('Contact Person'),
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
            'index' => Pages\ListShippingagents::route('/'),
            'create' => Pages\CreateShippingagent::route('/create'),
            'edit' => Pages\EditShippingagent::route('/{record}/edit'),
        ];
    }
}
