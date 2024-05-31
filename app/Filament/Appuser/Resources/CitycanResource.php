<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Citycan;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\CitycanResource\Pages;
use App\Filament\Appuser\Resources\CitycanResource\RelationManagers;

class CitycanResource extends Resource
{
    protected static ?string $model = Citycan::class;

    protected static ?string $navigationGroup = 'Canada Location';
    protected static ?string $navigationLabel = 'City';
    public static ?string $label = 'Canada City';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    Select::make('provincecan_id')
                ->relationship('Provincecan', 'name')->label('Canada Province'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)->label('Canada City'),
                ])->columns(2)

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->filtersTriggerAction(function($action){
            return $action->button()->label('Filters');
        })
            ->columns([
                Tables\Columns\TextColumn::make('provincecan.name')
                ->label('Province')
                ->sortable(),
            Tables\Columns\TextColumn::make('name')
                ->searchable(),
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
            SelectFilter::make('citycan_id')
            ->label('Canada Province')
            ->relationship('provincecan', 'name')
            ->multiple()
            ->searchable()
            ->preload()
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
            'index' => Pages\ListCitycans::route('/'),
            'create' => Pages\CreateCitycan::route('/create'),
            'edit' => Pages\EditCitycan::route('/{record}/edit'),
        ];
    }
}
