<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Zone;
use Filament\Tables;
use App\Models\Boxtype;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Agentcommision;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\AgentcommisionResource\Pages;
use App\Filament\Resources\AgentcommisionResource\RelationManagers;

class AgentcommisionResource extends Resource
{
    protected static ?string $model = Agentcommision::class;
    protected static ?string $navigationLabel = 'Agent Discount';
    public static ?string $label = 'Agent Discount';
    

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('agent_id')
                ->label('Agent Name')
                ->searchable()
                ->preload()
                    ->relationship('agent', 'full_name',
                    modifyQueryUsing: fn (Builder $query) => $query->where('agent_type', '1'))
                    ->required(),
                Forms\Components\Select::make('boxtype_id')
                    ->label('Box Type')
                    ->searchable()
                    ->preload()
                    ->relationship('boxtype', 'description')
                    ->required(),
                Forms\Components\Select::make('zone_id')
                    ->label('Zone')
                    ->searchable()
                    ->preload()
                    ->relationship('zone', 'description'),
                Forms\Components\TextInput::make('commision_amount')
                    ->required()
                    ->numeric(),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        // ->defaultGroup('agent.full_name')
        // ->groups([
        //     Group::make('agent.full_name')
        //         ->label('Agent name')
        //         ->collapsible()
        // ])
            ->columns([
                Tables\Columns\TextColumn::make('agent.full_name')
                    ->label('Agent Name')
                    ->sortable(),
                Tables\Columns\SelectColumn::make('boxtype_id')
                    ->label('Box Type')
                    ->options(Boxtype::all()->pluck('description', 'id'))
                    ->sortable(),
                Tables\Columns\SelectColumn::make('zone_id')
                    ->label('Zone')
                    ->options(Zone::all()->pluck('description', 'id'))
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('commision_amount')
                    ->label('Discount Amount')
                    // ->numeric(decimalPlaces: 2)
                    // ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.full_name')
                    ->label('Encoder')
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
                SelectFilter::make('agent_id')->label('Agent Name')
                ->searchable()
                    ->preload()
                    ->relationship('agent', 'full_name', fn (Builder $query) => $query->where('agent_type', '1')),
                SelectFilter::make('zone_id')->relationship('zone', 'description')
                ->searchable()
                ->preload()
                ->label('Area'),
                SelectFilter::make('boxtype_id')
                ->searchable()
                ->preload()
                ->relationship('boxtype', 'description')
                ->label('Boxtype'),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ReplicateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('Replicate')
                    ->label('Replicate')
                    ->icon('heroicon-o-clipboard-document')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        foreach ($records as $record) {
                            $record->replicate()->save();
                        }
                       Notification::make()
                        ->title('Replicate successfully')
                        ->success()
                        ->send();
                    }),
                    Tables\Actions\BulkAction::make('UpdateZone')
                    ->label('Update Zone')
                    ->icon('heroicon-o-map-pin')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('zone_id')
                        ->label('Zone')
                        ->searchable()
                        ->preload()
                        ->relationship('zone', 'description')
                        ->searchable()
                    ])
                    ->requiresConfirmation()
                    ->action(function (Collection $records, array $data): void {
                        foreach ($records as $record) {
                            $record->update([
                                'zone_id' =>  $data['zone_id'],
                            ]);
                        }
                       Notification::make()
                        ->title('Update successfully')
                        ->success()
                        ->send();
                    }),
                    Tables\Actions\BulkAction::make('UpdateCommision')
                    ->label('Update Discount Amount')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('info')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('commision_amount')
                        ->label('Discount Amount')
                        ->numeric()
                        ->required()
                    ])
                    ->requiresConfirmation()
                    ->action(function (Collection $records, array $data): void {
                        foreach ($records as $record) {
                            $record->update([
                                'commision_amount' =>  $data['commision_amount'],
                            ]);
                        }
                       Notification::make()
                        ->title('Update successfully')
                        ->success()
                        ->send();
                    }),
                    Tables\Actions\BulkAction::make('UpdateBoxtype')
                    ->label('Update Boxtype')
                    ->icon('heroicon-o-archive-box')
                    ->color('success')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('boxtype_id')
                        ->label('Box Type')
                        ->searchable()
                        ->preload()
                        ->relationship('boxtype', 'description')
                        ->searchable()
                    ])
                    ->requiresConfirmation()
                    ->action(function (Collection $records, array $data): void {
                        foreach ($records as $record) {
                            $record->update([
                                'boxtype_id' =>  $data['boxtype_id'],
                            ]);
                        }
                       Notification::make()
                        ->title('Update successfully')
                        ->success()
                        ->send();
                    }),
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
            'index' => Pages\ListAgentcommisions::route('/'),
            'create' => Pages\CreateAgentcommision::route('/create'),
            'edit' => Pages\EditAgentcommision::route('/{record}/edit'),
        ];
    }
}
