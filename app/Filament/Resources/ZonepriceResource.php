<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Branch;
use Filament\Forms\Form;
use App\Models\Zoneprice;
use Filament\Tables\Table;
use App\Models\Servicetype;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ZonepriceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ZonepriceResource\RelationManagers;

class ZonepriceResource extends Resource
{
    protected static ?string $model = Zoneprice::class;
    protected static ?string $navigationLabel = 'Zone Price';
    public static ?string $label = 'Zone Price';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('servicetype_id')
                ->label('Servicetype')
                ->options(Servicetype::all()->pluck('description', 'id'))
                ->searchable(),
            Select::make('boxtype_id')
                ->relationship('boxtype', 'id')
                ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->description} {$record->dimension}"),
            Select::make('zone_id')
                ->label('Zone')
                ->options(Zone::all()->pluck('description', 'id'))
                ->searchable(),
            Select::make('branch_id')
                ->label('Branch')
                ->options(Branch::all()->pluck('business_name', 'id'))
                ->searchable(),
            Forms\Components\TextInput::make('price')
                ->required(),
            Forms\Components\Textarea::make('note')
                ->maxLength(65535),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('servicetype.description')
                    ->label('Service')
                    ->sortable(),
                Tables\Columns\TextColumn::make('boxtype.description')
                    ->label('Box')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function (Model $record) {
                        return $record->boxtype->description . " " . $record->boxtype->dimension;
                    }),
                Tables\Columns\TextColumn::make('zone.description')
                    ->label('Area'),
                Tables\Columns\TextColumn::make('price')->money('USD')
                    ->label('Price'),
                Tables\Columns\TextColumn::make('boxtype.total_box')
                    ->label('Total Number Box'),
                Tables\Columns\TextColumn::make('branch.business_name'),
                Tables\Columns\TextColumn::make('note'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
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
            'index' => Pages\ListZoneprices::route('/'),
            'create' => Pages\CreateZoneprice::route('/create'),
            'edit' => Pages\EditZoneprice::route('/{record}/edit'),
        ];
    }
}
