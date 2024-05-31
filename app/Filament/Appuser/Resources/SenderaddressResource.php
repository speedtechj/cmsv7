<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Provincecan;
use App\Models\Senderaddress;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\SenderaddressResource\Pages;
use App\Filament\Appuser\Resources\SenderaddressResource\RelationManagers;

class SenderaddressResource extends Resource
{
    protected static ?string $model = Senderaddress::class;
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
              Forms\Components\TextInput::make('address')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('provincecan_id')
                ->relationship('provincecan', 'name')
                ->label('Province')
                ->searchable()
                ->preload()
                ->required()
                ->options(Provincecan::all()->pluck('name', 'id')->toArray())
                ->reactive()
                ->afterStateUpdated(fn (callable $set) => $set('citycan_id', null)),
            Forms\Components\Select::make('citycan_id')
                ->label('City')
                ->relationship('citycan', 'name')
                ->searchable()
                ->preload()
                ->required()
                ->options(function (callable $get) {
                    $province = Provincecan::find($get('provincecan_id'));
                    if (!$province) {
                        // return Citycan::all()->pluck('name', 'id');
                        return null;
                    }
                    return $province->citycan->pluck('name', 'id');
                }),
            Forms\Components\Select::make('quadrant')
            ->required()
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                
                Tables\Columns\TextColumn::make('address')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('provincecan.name')
                ->label('Province Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('citycan.name')
                ->label('City Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('quadrant')
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
                Tables\Columns\TextColumn::make('postal_code')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
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
            'index' => Pages\ListSenderaddresses::route('/'),
            'create' => Pages\CreateSenderaddress::route('/create'),
            'edit' => Pages\EditSenderaddress::route('/{record}/edit'),
        ];
    }
}
