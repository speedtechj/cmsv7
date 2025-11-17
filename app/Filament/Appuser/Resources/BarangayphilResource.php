<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cityphil;
use Filament\Forms\Form;
use App\Models\Zoneroute;
use Filament\Tables\Table;
use App\Models\Barangayphil;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\BarangayphilResource\Pages;
use App\Filament\Appuser\Resources\BarangayphilResource\RelationManagers;

class BarangayphilResource extends Resource
{
    protected static ?string $model = Barangayphil::class;
    protected static ?string $navigationGroup = 'Philippines Location';
    protected static ?string $navigationLabel = 'Barangay';
    public static ?string $label = 'Philippine Barangay';
  
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cityphil_id')
                ->relationship('Cityphil', 'name')->label('Philippines City'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                  Tables\Columns\TextColumn::make('province.name')->label('Province Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('cityphil.name')->label('City Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('Barangay Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                 Tables\Columns\TextColumn::make('zoneroute.route_name')->label('Warehouse Route')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                 Filter::make('location') // A custom filter with a form
                ->form([
                    Select::make('province_id')
                        ->label('Province')
                        ->preload()
                        ->relationship('province', 'name')
                        ->searchable()
                        ->live()
                       ->afterStateUpdated(fn (callable $set) => $set('city_id', null)),

                    Select::make('city_id')
                        ->label('City')
                        ->options(fn (callable $get) => $get('province_id')
                            ? Cityphil::where('provincephil_id', $get('province_id'))->pluck('name', 'id')->toArray()
                            : Cityphil::pluck('name', 'id')->toArray()
                        )
                        ->searchable(),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['province_id'],
                            fn (Builder $query, $provinceId): Builder =>
                                $query->whereHas('cityphil', fn (Builder $query) => $query->where('provincephil_id', $provinceId)),
                        )
                        ->when(
                            $data['city_id'],
                            fn (Builder $query, $cityId): Builder =>
                                $query->where('cityphil_id', $cityId),
                        );
                }),
               SelectFilter::make('zoneroute_id')->label('Warehouse Route')
                ->relationship('zoneroute', 'route_name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                       Tables\Actions\BulkAction::make('Set Warehouse Route')
                    ->label('Warehouse Route')
                    ->icon('heroicon-o-map-pin')
                    ->color('primary')
                   
                    ->form([
                        Forms\Components\Select::make('zoneoute_id')
                            ->label('Warehouse Route')
                            ->options(Zoneroute::all()->pluck('route_name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                    ])
                    ->action(function (Collection $records, array $data): void {

                        foreach ($records as $record) {
                            $record->update([

                                'zoneroute_id' => $data['zoneoute_id'],
                            ]);
                       
                        // foreach ($records as $record) {
                        //     $record->update([
    
                        //         'no_days' => $data['no_days'],
                        //     ]);
                        }
                        Notification::make()
                        ->title('Saved successfully')
                        ->success()
                        ->send();
                    })
                     ->requiresConfirmation(),
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
            'index' => Pages\ListBarangayphils::route('/'),
            'create' => Pages\CreateBarangayphil::route('/create'),
            'edit' => Pages\EditBarangayphil::route('/{record}/edit'),
        ];
    }
}
