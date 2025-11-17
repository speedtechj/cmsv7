<?php

namespace App\Filament\Appuser\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Cityphil;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Appuser\Resources\CityphilResource\Pages;
use App\Filament\Appuser\Resources\CityphilResource\RelationManagers;
use App\Filament\Appuser\Resources\CityphilResource\RelationManagers\BarangayphilRelationManager;
use App\Models\Zoneroute;
use Filament\Infolists\Components\Tabs;
use Filament\Notifications\Notification;

class CityphilResource extends Resource
{
    protected static ?string $model = Cityphil::class;

    protected static ?string $navigationGroup = 'Philippines Location';
    protected static ?string $navigationLabel = 'City/Town';
    public static ?string $label = 'Philippine City/Town';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('provincephil_id')
                ->relationship('Provincephil', 'name')->label('Philippines Province'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                    Select::make('zone_id')
                    ->label('Zone')
                    ->options(Zone::all()->pluck('description', 'id'))
                    ->searchable(),
                // Select::make('zoneroute_id')
                //     ->label('Zone')
                //     ->options(Zoneroute::all()->pluck('route_name', 'id'))
                //     ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('provincephil.name')
                ->label('Province Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('name')->label('City/Town Name')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('zone.description')->label('Zone')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('no_days')->label('No of Days')
                ->searchable()
                ->toggleable()
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('zone_id')->relationship('zone', 'description')
                ->preload()
                ->label('Location Zone')
                ->searchable(),
                SelectFilter::make('provincephil_id')->relationship('provincephil', 'name')
                ->label('Province Name')
                ->preload()
                ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    //    Tables\Actions\BulkAction::make('Set Warehouse Route')
                    // ->label('Warehouse Route')
                    // ->icon('heroicon-o-map-pin')
                    // ->color('primary')
                   
                    // ->form([
                    //     Forms\Components\Select::make('zoneoute_id')
                    //         ->label('Warehouse Route')
                    //         ->options(Zoneroute::all()->pluck('route_name', 'id'))
                    //         ->searchable()
                    //         ->preload()
                    //         ->required()
                    // ])
                    // ->action(function (Collection $records, array $data): void {

                    //     foreach ($records as $record) {
                    //         $record->update([

                    //             'zoneroute_id' => $data['zoneoute_id'],
                    //         ]);
                       
                    //     // foreach ($records as $record) {
                    //     //     $record->update([
    
                    //     //         'no_days' => $data['no_days'],
                    //     //     ]);
                    //     }
                    //     Notification::make()
                    //     ->title('Saved successfully')
                    //     ->success()
                    //     ->send();
                    // })
                    //  ->requiresConfirmation(),
                
                    // Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Set days')
                    // ->slideOver()
                    ->label('Update No of Days')
                    ->icon('heroicon-o-calendar-days')
                    ->color('primary')
                   
                    ->form([
                        Forms\Components\TextInput::make('no_days')
                            ->label('No of Days')
                            ->required()
                    ])
                    ->action(function (Collection $records, array $data): void {
                       
                        foreach ($records as $record) {
                            $record->update([
    
                                'no_days' => $data['no_days'],
                            ]);
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
            BarangayphilRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCityphils::route('/'),
            'create' => Pages\CreateCityphil::route('/create'),
            'edit' => Pages\EditCityphil::route('/{record}/edit'),
        ];
    }
}
